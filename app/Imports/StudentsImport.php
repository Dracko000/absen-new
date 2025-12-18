<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\Attendance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    private $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Validate the required fields
            $validator = Validator::make($row->toArray(), [
                'nama' => 'required|string|max:255',
                'nis' => 'required|string',
            ]);

            if ($validator->fails()) {
                // Log validation errors or handle as needed
                continue;
            }

            // Check if the class exists
            $class = ClassModel::find($this->classId);
            if (!$class) {
                continue; // Skip if class doesn't exist
            }

            // Check if a user with this NIS already exists
            $existingUser = User::where('nis', $row['nis'])->first();
            if ($existingUser) {
                // If user already exists, check if they're already assigned to a different class
                // by checking attendance records for other classes
                $existingAttendanceInOtherClass = Attendance::where('user_id', $existingUser->id)
                    ->where('class_model_id', '!=', $this->classId)
                    ->exists();

                if ($existingAttendanceInOtherClass) {
                    // Skip adding this student to another class since they're already in a different class
                    continue;
                } else {
                    // Student exists but is not in another class, they can be associated with this class
                    // We don't need to create a new user, just continue to process
                }
            } else {
                // Create the new student user if one doesn't exist
                $user = User::create([
                    'name' => $row['nama'],
                    'email' => $row['nis'] . '@student.example.com', // Generate a default email
                    'nis' => $row['nis'],
                    'password' => Hash::make('defaultpassword'), // Default password
                ]);

                // Assign student role
                $user->assignRole('User');
            }
        }
    }
}