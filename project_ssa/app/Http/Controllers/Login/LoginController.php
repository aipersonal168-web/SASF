<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
 use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    public function login(Request $request)
{
    // 1️⃣ Login via API
    $loginResponse = Http::post('http://localhost:5000/api/users/', [
        'name' => $request->name,
        'password' => $request->password,
        'role' => $request->role,
    ]);

    if ($loginResponse->failed()) {
        return back()->withErrors([
            'login' => 'Login failed! Invalid username or password.'
        ]);
    }

    $user = $loginResponse->json();
    Session::put('user', $user);

    // 2️⃣ Fetch students from API
    $studentResponse = Http::get('http://localhost:5000/api/students/getAll');

    if ($studentResponse->failed()) {
        return back()->with('error', 'Backend error: ' . $studentResponse->body());
    }

    $students = $studentResponse->json(); // ✅ decode JSON to array
// dd($students);
    // 3️⃣ Pass data to Blade view
    return view('dashboard.index', compact('students'));
}

public function logout(Request $request)
{
    Auth::logout(); // Logs out the user
    $request->session()->invalidate(); // Invalidate session
    $request->session()->regenerateToken(); // Regenerate CSRF token

    return redirect('/'); // Redirect to login page
}
}