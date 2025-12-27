@extends('layouts.app')

@section('title', 'Attendance')
@section('menu-attendance', 'active')

@section('content')



@php
use Carbon\Carbon;

// Week 1 begins
$week1Start = Carbon::createFromFormat('d/m/Y', '20/9/2025');

// Current date
$currentDate = Carbon::now(); // Or use Carbon::createFromFormat('d/m/Y', '12/11/2025');

// If the current date is before the schedule
if ($currentDate->lt($week1Start)) {
$weekNumber = 0;
} else {
// Count the number of full weeks since the start + 1
$weekNumber1 = $week1Start->diffInWeeks($currentDate) + 1;
$weekNumber = intval($weekNumber1);

// Optional: limit to a maximum of 15 weeks
if ($weekNumber > 15) {
$weekNumber = 0;
}
}


// Convert year numbers to Khmer numerals





@endphp
<style>
    .attendance-table th {
        background: #0d6efd;
        color: white;
        text-align: center;
    }

    .attendance-table td {
        text-align: center;
        vertical-align: middle;
    }

    .status-group label {
        margin-right: 10px;
        font-weight: 600;
    }
</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Teacher Attendance</h2>
        <p class="fw-bold mb-0">Week = {{ $weekNumber }}</p>
    </div>
    <input type="text" id="week" value="{{ $weekNumber }}" hidden>
    {{-- Filter Section --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <label class="form-label fw-bold"><strong>Year</strong></label>
            <select id="year" class="form-select">
                <option value="" selected disabled hidden>សូមជ្រើសរើស</option>
                @foreach ($datas as $item)
                <option value="{{ $item['id'] }}">
                    ឆ្នាំទី {{ $item['name'] }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold"><strong>Semester</strong></label>
            <select id="semester" class="form-select">
                <option value="" selected disabled hidden>សូមជ្រើសរើស</option>
                @foreach ($semesters as $item)
                <option value="{{ $item['id'] }}">
                    ឆមាសលើកទី {{ $item['name'] }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold"><strong>Shift</strong></label>
            <select id="shift" class="form-select">
                <option value="" selected disabled hidden>សូមជ្រើសរើស</option>
                @foreach ($shifts as $item)
                <option value="{{ $item['studentId'] }}">
                    វេនសិក្សា {{ $item['name'] }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold"><strong>Room</strong></label>
            <select id="room" class="form-select">
                <option value="" selected disabled hidden>សូមជ្រើសរើស</option>
                @foreach ($rooms as $item)
                <option value="{{ $item['name'] }}">
                    បន្ទប់សិក្សា {{ $item['name'] }}
                </option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- 🔍 Search Button --}}
    <div class="text-end mb-3">
        <button class="btn btn-primary px-4" onclick="autoSearch()">
            🔍 Search
        </button>
    </div>

    <div class="card shadow p-4">

        <table class="table table-bordered attendance-table">
            <thead>
                <tr>
                    <th>លេខរៀង</th>
                    <th>លេខកាតសិស្ស</th>
                    <th>ឈ្មោះសិស្ស</th>
                    <th>ភេទ</th>
                    <th>វត្តមានសិស្ស</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        សូមជ្រើសរើសលក្ខខណ្ឌស្វែងរក
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-3">
        <button class="btn btn-success px-4" onclick="storeAttendance()">Save Attendance</button>
    </div>

</div>


@section('scripts')

@include('dashboard.attendance.footer')

@endsection


@endsection