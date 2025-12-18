<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToCollection, WithHeadingRow
{
    protected $role;

    public function __construct($role = 'User')
    {
        $this->role = $role;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            // Skip empty rows
            if (empty($row['name']) && empty($row['email'])) {
                continue;
            }

            $name = $row['name'] ?? '';
            $email = $row['email'] ?? '';
            $identifier = $this->role === 'User' ? ($row['nis'] ?? '') : ($row['nip_nuptk'] ?? '');

            // Validate the row data before creating user
            $validator = Validator::make($row->toArray(), $this->rules());

            if ($validator->fails()) {
                // Throw an exception to be caught by the controller
                throw new \Exception("Row validation failed: " . $validator->errors()->first());
            }

            // Default password will be the identifier value
            $password = $identifier ?: time();

            // Check if user already exists
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                throw new \Exception("User with email {$email} already exists.");
            }

            // Create the user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $this->role, // Set role directly
                'nis' => $this->role === 'User' ? $identifier : null,
                'nip_nuptk' => $this->role === 'Admin' ? $identifier : null,
            ]);

            // Assign role using spatie/laravel-permission
            $user->assignRole($this->role);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nis' => Rule::when($this->role === 'User', ['required', 'string', 'unique:users,nis']),
            'nip_nuptk' => Rule::when($this->role === 'Admin', ['required', 'string', 'unique:users,nip_nuptk']),
        ];
    }
}