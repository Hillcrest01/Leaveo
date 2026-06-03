@extends('layouts.app')

@section('content')
<style>
    /* Premium Calendar Designtokens Override */
    .calendar-container {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
    }

    .calendar-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    
    .calendar-table th {
        background-color: #f8f9fa;
        color: #231F20;
        font-size: 0.815rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem;
        border-bottom: 2px solid #e9ecef;
        border-right: 1px solid #e9ecef;
    }

    .calendar-table th:last-child {
        border-right: none;
    }
    
    .calendar-table td {
        border-bottom: 1px solid #e9ecef;
        border-right: 1px solid #e9ecef;
        padding: 0.5rem;
        transition: background-color 0.15s ease;
    }

    .calendar-table td:last-child {
        border-right: none;
    }

    .calendar-table tr:last-child td {
        border-bottom: none;
    }
    
    /* Elegant Solid Entry Badges instead of saturated text blocks */
    .leave-entry-badge {
        background-color: #f8f9fa;
        border-left: 3px solid #F15929;
        border-radius: 0 4px 4px 0;
        padding: 0.375rem 0.5rem;
        font-size: 0.775rem;
        line-height: 1.3;
        margin-top: 0.375rem;
        border-top: 1px solid #e9ecef;
        border-right: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
    }

    /* Professional Navigation Components */
    .btn-nav-control {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #231F20;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        height: 38px;
        padding: 0 1rem;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .btn-nav-control:hover {
        background-color: #f8f9fa;
        border-color: #b8bcca;
        color: #231F20;
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            
            {{-- Master Structural Content Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0" style="font-weight: 700; color: #231F20; letter-spacing: -0.02em;">Leave Calendar</h1>
                </div>
            </div>

            <div class="calendar-container p-4">
                {{-- Calendar Navigation Dashboard Controller --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('hr.leave-requests.calendar', ['year' => $year, 'month' => $month-1]) }}" class="btn btn-nav-control">
                        &larr; Previous Month
                    </a>
                    <h2 class="h4 mb-0" style="color: #231F20; font-weight: 700; letter-spacing: -0.01em;">
                        {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
                    </h2>
                    <a href="{{ route('hr.leave-requests.calendar', ['year' => $year, 'month' => $month+1]) }}" class="btn btn-nav-control">
                        Next Month &rarr;
                    </a>
                </div>
                
                @php
                    $date = \Carbon\Carbon::create($year, $month, 1);
                    $daysInMonth = $date->daysInMonth;
                    $firstDayOfWeek = $date->dayOfWeek;
                    $weeks = ceil(($daysInMonth + $firstDayOfWeek) / 7);
                @endphp
                
                {{-- Grid Matrix Layout Box Frame --}}
                <div class="table-responsive">
                    <table class="table calendar-table">
                        <thead>
                            <tr class="text-center">
                                <th>Sunday</th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                                <th>Saturday</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($week = 0; $week < $weeks; $week++)
                                <tr>
                                    @for($day = 0; $day < 7; $day++)
                                        @php
                                            $currentDay = ($week * 7) + $day - $firstDayOfWeek + 1;
                                            $dateKey = $currentDay >= 1 && $currentDay <= $daysInMonth 
                                                ? \Carbon\Carbon::create($year, $month, $currentDay)->format('Y-m-d')
                                                : null;
                                            $leaves = $dateKey && isset($calendarData[$dateKey]) ? $calendarData[$dateKey] : [];
                                        @endphp
                                        <td style="height: 120px; width: 14.28%; vertical-align: top; background-color: #FFFFFF;">
                                            @if($dateKey)
                                                <div class="fw-bold mb-1 ps-1" style="color: #231F20; font-size: 0.95rem;">{{ $currentDay }}</div>
                                                <div style="max-height: 85px; overflow-y: auto;">
                                                    @foreach($leaves as $leave)
                                                        <div class="leave-entry-badge">
                                                            <div style="font-weight: 700; color: #231F20;">{{ $leave['employee'] }}</div>
                                                            <div class="text-muted" style="font-size: 0.725rem;">{{ $leave['type'] }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection