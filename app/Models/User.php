<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'employee_id', 'department', 'join_date', 'phone', 'gender', 'role', 'manager_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function manager(){
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function subordinates(){
        return $this->hasMany(User::class, 'manager_id');
    }
    public function leaveBalances():HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }
    public function leaveRequests():HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
    public function approvedLeaves(){
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }
}
