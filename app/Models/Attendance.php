<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'class_model_id',
        'date',
        'time_in',
        'time_out',
        'status', // 'Hadir', 'Terlambat', 'Tidak Hadir'
        'note'
    ];

    /**
     * Define relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define relationship with ClassModel
     */
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_model_id');
    }
}
