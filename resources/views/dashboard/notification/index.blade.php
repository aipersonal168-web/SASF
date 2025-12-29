<!-- resources/views/attendance_notifications.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>🔔 Notifications</h3>
{{-- @dd($data); --}}
    <ul class="list-group mt-3">
        @foreach($data as $student)
        <li class="list-group-item d-flex align-items-start">
            <!-- Icon based on status -->
            <span class="me-3">
                @if($student['status'] === 'WARNING')
                    <span class="badge bg-warning text-dark rounded-circle p-3">⚠️</span>
                @elseif($student['status'] === 'DROP_RISK')
                    <span class="badge bg-danger rounded-circle p-3">🚨</span>
                @else
                    <span class="badge bg-success rounded-circle p-3">✅</span>
                @endif
            </span>

            <div class="flex-grow-1">
                <div class="fw-bold">{{ $student['name'] }} ({{ $student['id'] }})</div>
                <div>Status: {{ ucfirst($student['status']) }}</div>
                <div class="text-muted">{{ $student['prediction'] }}</div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
@endsection
