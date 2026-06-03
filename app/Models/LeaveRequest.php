<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = [
         'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'remarks',
        'approved_by',
        'approved_at',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function leaveType():BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
    public function approver():BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending():bool{
        return $this->status === 'pending';
    }
    public function isApproved():bool{
        return $this->status === 'approved';
    }
    public function isRejected():bool{
        return $this->status === 'rejected';
    }
}
