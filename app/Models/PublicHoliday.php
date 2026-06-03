<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'holiday_date',
        'description',
        'is_recurring',
        'year',
        'is_active',
    ];

    protected $casts = [
        'holiday_date' => 'date',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
    ];

     public static function isHoliday($date)
    {
        $dateObj = \Carbon\Carbon::parse($date);
        
        // Check for specific year holiday
        $exists = self::where('holiday_date', $dateObj->format('Y-m-d'))
            ->where('is_active', true)
            ->exists();
        
        if ($exists) {
            return true;
        }
        
        // Check for recurring holiday (same month and day)
        $exists = self::whereMonth('holiday_date', $dateObj->month)
            ->whereDay('holiday_date', $dateObj->day)
            ->where('is_recurring', true)
            ->where('is_active', true)
            ->exists();
        
        return $exists;
    }

    /**
     * Get holidays between two dates
     */
    public static function getHolidaysInRange($startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        
        $holidays = collect();
        
        // Get specific year holidays
        $specificHolidays = self::whereBetween('holiday_date', [$start, $end])
            ->where('is_active', true)
            ->get();
        
        $holidays = $holidays->merge($specificHolidays);
        
        // Get recurring holidays within date range
        $current = $start->copy();
        while ($current <= $end) {
            $recurring = self::whereMonth('holiday_date', $current->month)
                ->whereDay('holiday_date', $current->day)
                ->where('is_recurring', true)
                ->where('is_active', true)
                ->first();
            
            if ($recurring && !$holidays->contains('holiday_date', $current->format('Y-m-d'))) {
                // Clone to avoid modifying the original
                $holiday = clone $recurring;
                $holiday->holiday_date = $current->copy();
                $holidays->push($holiday);
            }
            
            $current->addDay();
        }
        
        return $holidays->unique('holiday_date');
    }
     public static function countHolidaysInRange($startDate, $endDate)
    {
        return self::getHolidaysInRange($startDate, $endDate)->count();
    }

}
