<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'days_per_year',
        'gender_restriction',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'days_per_year' => 'integer'
    ];

    public function leaveBalances():HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
    public function isAvailableForGender($gender): bool{
        if($this->gender_restriction === 'both'){
            return true;
        }
        return $this->gender_restriction === $gender;
    }
    public function getGenderRestrictionLabelAttribute(): string
{
    return match($this->gender_restriction) {
        'male' => 'Male Only',
        'female' => 'Female Only',
        default => 'Both Genders',
    };
}
}
