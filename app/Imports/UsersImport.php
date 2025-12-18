<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Attendance;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\Importable;

class UsersImport implements ToCollection, WithHeadingRow
{
    use Importable;  // This trait provides the import() method

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
            if (empty($row['name']) && empty($row['nis'] ?? '') && empty($row['nip_nuptk'] ?? '')) {
                continue;
            }

            $name = $row['name'] ?? '';
            $identifier = $this->role === 'User' ? ($row['nis'] ?? '') : ($row['nip_nuptk'] ?? '');

            // For students, generate email from NIS
            if ($this->role === 'User') {
                $email = $identifier . '@student.example.com';
            } else {
                // For teachers, use provided email or generate from nip_nuptk
                $email = $row['email'] ?? ($identifier . '@teacher.example.com');
            }

            // Validate the row data before creating user
            $validator = Validator::make($row->toArray(), $this->rules());

            if ($validator->fails()) {
                // Throw an exception to be caught by the controller
                throw new \Exception("Row validation failed: " . $validator->errors()->first());
            }

            // Password will be the identifier value (NIS for students, NIP/Nuptk for teachers)
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
                'password' => Hash::make($password), // Use identifier as password
                'role' => $this->role,
                'nis' => $this->role === 'User' ? $identifier : null,
                'nip_nuptk' => $this->role === 'Admin' ? $identifier : null,
            ]);

            // Assign role using spatie/laravel-permission
            $user->assignRole($this->role);

            // If it's a student and class_id is provided, create attendance record to assign to class
            if ($this->role === 'User' && !empty($row['class_id'])) {
                $class = ClassModel::find($row['class_id']);
                if ($class) {
                    // Create attendance record to associate student with class
                    Attendance::create([
                        'user_id' => $user->id,
                        'class_model_id' => $class->id,
                        'date' => now()->toDateString(),
                        'time_in' => now()->toTimeString(),
                        'status' => 'Tidak Hadir', // Default status when first assigned
                        'note' => 'Siswa diimpor ke kelas',
                    ]);
                }
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nis' => Rule::when($this->role === 'User', ['required', 'string', 'unique:users,nis']),
            'nip_nuptk' => Rule::when($this->role === 'Admin', ['required', 'string', 'unique:users,nip_nuptk']),
            'class_id' => Rule::when($this->role === 'User', ['nullable', 'exists:class_models,id']),
        ];
    }
}