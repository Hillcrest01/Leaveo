<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PublicHoliday;

class PublicHolidaysTableSeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            ['name' => 'New Year\'s Day', 'month' => 1, 'day' => 1, 'recurring' => true],
            ['name' => 'Good Friday', 'month' => 4, 'day' => 18, 'recurring' => false], // Changes yearly
            ['name' => 'Easter Monday', 'month' => 4, 'day' => 21, 'recurring' => false],
            ['name' => 'Labour Day', 'month' => 5, 'day' => 1, 'recurring' => true],
            ['name' => 'Madaraka Day', 'month' => 6, 'day' => 1, 'recurring' => true],
            ['name' => 'Mashujaa Day', 'month' => 10, 'day' => 20, 'recurring' => true],
            ['name' => 'Jamhuri Day', 'month' => 12, 'day' => 12, 'recurring' => true],
            ['name' => 'Christmas Day', 'month' => 12, 'day' => 25, 'recurring' => true],
            ['name' => 'Boxing Day', 'month' => 12, 'day' => 26, 'recurring' => true],
        ];
        
        foreach ($holidays as $holiday) {
            if ($holiday['recurring']) {
                $date = date('Y') . '-' . str_pad($holiday['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($holiday['day'], 2, '0', STR_PAD_LEFT);
                PublicHoliday::create([
                    'name' => $holiday['name'],
                    'holiday_date' => $date,
                    'is_recurring' => true,
                    'is_active' => true,
                ]);
            }
        }
    }
}