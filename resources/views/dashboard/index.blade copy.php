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

<div class="cards">

    <div class="card">
        <h4>Overall Attendance 👥</h4>
        <p>87%</p>
        <p>113 of 130 students present</p>
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
    </div>

    <div class="card">
        <h4>Present Today 👤</h4>
        <p>113</p>
        <small>Students marked present</small>
    </div>

    <div class="card">
        <h4>Absent Today 👤</h4>
        <p>17</p>
        <small>Students absent</small>
    </div>

    <div class="card">
        <h4>Notifications 🔔</h4>
        <p>3</p>
        <small>Require attention</small>
    </div>

</div>




<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f6fa;
        padding: 20px;
    }

    .section {
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        width: 950px;
        /* FIXED WIDTH */
        margin: 20px;
        /* CENTER SECTION */
    }

    .section canvas {
        max-width: 100% !important;
        height: 280px !important;
        /* CONTROL HEIGHT */
        display: block;
        margin: 0 auto;
    }
</style>
</head>

<body>

    <div class="section">
        <h3>Most Irregular Students</h3>
        <p>Students with highest absences</p>

        <!-- BAR CHART -->
        <canvas id="irregularStudentsChart" height="180"></canvas>
    </div>

    <script>
        // STATIC DATA (replace with DB later)
    const studentNames = ["Em Meut", "John", "Sok", "Vannak","a","op","Em Meut", "John", "Sok", "Vannak","a","op"];
    const absenceDays = [10, 8, 7, 5];

    // CHART
    const ctx = document.getElementById('irregularStudentsChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: studentNames,
            datasets: [{
                label: 'Absence Days',
                data: absenceDays,
                backgroundColor: "rgba(255, 99, 132, 0.7)",
                borderRadius: 8,
                barThickness: 35
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
    </script>





    @endsection