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

<script>
    async function fetchGBFS() {
    try {
        // Direct API call
        const response = await fetch('http://localhost:5000/gbfs/predict');
        const data = await response.json();

        // Update total safely
        document.getElementById('gbfs-total').innerText = data.total ?? 0;
        
    } catch (error) {
        console.error('Error fetching GBFS:', error);
    }
}

// Initial fetch on page load
window.onload = fetchGBFS;

// Auto-fetch every 5 seconds
setInterval(fetchGBFS, 5000);

</script>


<script>
    async function fetchDashboardData() {
    try {
        // 1️⃣ Fetch total students
        const studentResponse = await fetch('http://localhost:5000/api/students/getAll');
        const studentData = await studentResponse.json();
        const studentCount = Array.isArray(studentData) ? studentData.length : 0;
        document.getElementById('student-total').innerText = studentCount;


    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    }
}

// ✅ Run on page load
window.addEventListener('load', fetchDashboardData);

// ✅ Auto-fetch every 5 seconds
setInterval(fetchDashboardData, 5000);
</script>