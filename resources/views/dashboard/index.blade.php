@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')




<div class="top-header">
    <h2>Smart Attendance</h2>
    <div class="date" id="today"></div>
</div>

<script>
    function update() {
    const d = new Date();
    const t = d.toLocaleString('en-US', {
        weekday: 'short',  // Mon
        day: '2-digit',    // 05
        month: 'short',    // Dec
        year: 'numeric',   // 2025
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('today').textContent = "📅 " + t;
}
update();
setInterval(update, 1000);
</script>


<h3>Attendance Dashboard</h3>
<p>Today's attendance overview</p>


<div class="row">
    <!-- Overall Attendance -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4>Student New 👥</h4>
                <p class="fs-3 fw-bold" id="student-total"></p>
                <h2>Total Students: {{ session('studentCount', 0) }}</h2>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4>Notifications 🔔</h4>
                <p class="fs-3 fw-bold" id="gbfs-total">
            </div>
        </div>
    </div>
</div>

</div>

@endsection