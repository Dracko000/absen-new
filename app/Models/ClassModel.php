<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'teacher_id',  // ID of the teacher who teaches this class
        'entry_time',  // Class entry time
        'exit_time',   // Class exit time
    ];

    /**
     * Define relationship with User model (teacher)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Define relationship with Attendance model
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Define relationship with Schedule model
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get all students in this class through attendance records
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'attendances', 'class_model_id', 'user_id')
                    ->whereHas('roles', function($query) {
                        $query->where('name', 'User');
                    });
    }
}
