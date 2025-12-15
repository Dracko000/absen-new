<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Adding role field to store user type
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Define relationship with Attendance model
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get QR code for this user
     */
    public function getQrCodeAttribute()
    {
        return route('user.qr.show', ['id' => $this->id]);
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('Superadmin');
    }

    /**
     * Check if user is admin (teacher)
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if user is student
     */
    public function isStudent()
    {
        return $this->hasRole('User');
    }
}
