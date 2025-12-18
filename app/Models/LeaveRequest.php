<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'approved_by',
        'reason',
        'attachment',
        'start_date',
        'end_date',
        'status',
        'notes',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship with User model (student who requested leave)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with User model (admin who approved)
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}