<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Attendance')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/script.js') }}"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
        }

        .layout {
            display: flex;
            width: 100%;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #ffffff;
            min-height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
        }

        .profile {
            text-align: left;
            margin-bottom: 30px;
        }

        .profile img {
            width: 60px;
            border-radius: 50%;
        }

        .profile h3 {
            margin: 10px 0 0;
            font-size: 18px;
        }

        .nav-title {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            cursor: pointer;
            border-radius: 10px;
            font-size: 16px;
            transition: 0.3s;
        }

        .menu-item:hover {
            background: #ececec;
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 30px;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            background: #dcdcdc;
            height: 10px;
            border-radius: 20px;
        }

        .progress-fill {
            height: 100%;
            background: #4caf50;
            width: 87%;
            border-radius: 20px;
        }

        .section {
            background: #d1d1d1;
            padding: 25px;
            border-radius: 15px;
            margin-top: 30px;
        }

        .student-card {
            background: #fff;
            padding: 18px;
            border-radius: 12px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
    </style>

</head>

<body>
    <?php
use Illuminate\Support\Facades\Session;

$sessionData = Session::get('user'); 
// dd($sessionData);
// If your session stores user info directly:
$userImage = $sessionData['user']['images'] ?? null;
$userName  = $sessionData['user']['name'] ?? null;

// Check if not admin
if ($userName !== 'admin') {
    $userName = 'User';
}
?>

    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile">
                <img src="{{ $userImage ? asset('image_user/' . $userImage) : asset('default.png') }}" alt="User Image"
                    width="60">

                <h3>Smart Attendance (<strong>{{ $userName }}</strong>)</h3>
            </div>
            @if($userName !== 'admin')
            <div class="menu-item" onclick="goTo('/attendance')">📝 Attendance</div>
            <div class="menu-item" onclick="goTo('/notifications')">🔔 Notifications</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item  mt-3" style="all: unset; cursor: pointer;">
                    🚪 Logout
                </button>
            </form>



            @else
            <div class="menu-item" onclick="goTo('/dashboard')">📊 Dashboard</div>
            <div class="menu-item" onclick="goTo('/students')">🎓 Student</div>
            <div class="menu-item" onclick="goTo('/attendance')">📝 Attendance</div>
            <div class="menu-item" onclick="goTo('/notifications')">🔔 Notifications</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item  mt-3" style="all: unset; cursor: pointer;">
                    🚪 Logout
                </button>
            </form>

            @endif
        </div>

        <!-- Main Content Area -->
        <div class="main">
            @yield('content')
        </div>

    </div>
    <!-- Scripts -->
    @yield('scripts')
</body>

</html>

<script>
    function logout() {
    // Remove any saved tokens or user info
    localStorage.removeItem('authToken'); 
    sessionStorage.removeItem('user');

    // Redirect to login page
    window.location.href = '/login.html';
}

// Example usage
document.getElementById('logoutBtn').addEventListener('click', logout);

<script>