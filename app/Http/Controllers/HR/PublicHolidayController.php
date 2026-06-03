<?php

namespace App\Http\Controllers\hr;

use App\Http\Controllers\Controller;
use App\Models\PublicHoliday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicHolidayController extends Controller
{
    /**
     * Display list of public holidays
     */
    public function index()
    {
        $currentYear = Carbon::now()->year;
        
        $holidays = PublicHoliday::orderBy('holiday_date', 'desc')
            ->orderBy('year', 'desc')
            ->paginate(15);
        
        $years = PublicHoliday::selectRaw('DISTINCT YEAR(holiday_date) as year')
            ->pluck('year')
            ->sort()
            ->reverse();
        
        return view('hr.public-holidays.index', compact('holidays', 'years', 'currentYear'));
    }

    /**
     * Show form to create holiday
     */
    public function create()
    {
        return view('hr.public-holidays.create');
    }

    /**
     * Store new holiday
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'holiday_date' => 'required|date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'year' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        // If not recurring, store the year
        if (!$validated['is_recurring']) {
            $validated['year'] = Carbon::parse($validated['holiday_date'])->year;
        } else {
            $validated['year'] = null;
        }
        
        PublicHoliday::create($validated);
        
        return redirect()->route('hr.public-holidays.index')
            ->with('success', 'Public holiday added successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(PublicHoliday $publicHoliday)
    {
        return view('hr.public-holidays.edit', compact('publicHoliday'));
    }

    /**
     * Update holiday
     */
    public function update(Request $request, PublicHoliday $publicHoliday)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'holiday_date' => 'required|date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'year' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        if (!$validated['is_recurring']) {
            $validated['year'] = Carbon::parse($validated['holiday_date'])->year;
        } else {
            $validated['year'] = null;
        }
        
        $publicHoliday->update($validated);
        
        return redirect()->route('hr.public-holidays.index')
            ->with('success', 'Public holiday updated successfully!');
    }

    /**
     * Delete holiday
     */
    public function destroy(PublicHoliday $publicHoliday)
    {
        $publicHoliday->delete();
        
        return redirect()->route('hr.public-holidays.index')
            ->with('success', 'Public holiday deleted successfully!');
    }

    /**
     * Toggle holiday status
     */
    public function toggleStatus(PublicHoliday $publicHoliday)
    {
        $publicHoliday->is_active = !$publicHoliday->is_active;
        $publicHoliday->save();
        
        $status = $publicHoliday->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('hr.public-holidays.index')
            ->with('success', "Holiday {$status} successfully!");
    }

    /**
     * Bulk import holidays for a year
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'holidays' => 'required|array',
            'holidays.*.name' => 'required|string',
            'holidays.*.date' => 'required|date',
        ]);
        
        $imported = 0;
        $skipped = 0;
        
        foreach ($request->holidays as $holiday) {
            $exists = PublicHoliday::where('holiday_date', $holiday['date'])
                ->where(function($q) use ($request) {
                    $q->where('year', $request->year)
                      ->orWhere('is_recurring', true);
                })
                ->exists();
            
            if (!$exists) {
                PublicHoliday::create([
                    'name' => $holiday['name'],
                    'holiday_date' => $holiday['date'],
                    'year' => $request->year,
                    'is_recurring' => false,
                    'is_active' => true,
                ]);
                $imported++;
            } else {
                $skipped++;
            }
        }
        
        return redirect()->route('hr.public-holidays.index')
            ->with('success', "Imported {$imported} holidays. Skipped {$skipped} duplicates.");
    }
}