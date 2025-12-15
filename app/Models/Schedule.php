<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'class_model_id',
        'subject',
        'start_time',
        'end_time',
        'day_of_week',
        'date'
    ];

    /**
     * Define relationship with ClassModel
     */
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_model_id');
    }
}
