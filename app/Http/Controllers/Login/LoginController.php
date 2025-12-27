<?php

namespace App\Http\Controllers\Login;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Use the Http facade
use Illuminate\Support\Facades\Session;
 use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
     
public function index()
{
    try {
        return view('index');
    } catch (\Exception $e) {
        // Handle the exception
        // For example, log it or show an error page
        \Log::error($e->getMessage());

        return response()->view('errors.500', [], 500);
    }
}






public function logout(Request $request)
{
    Auth::logout(); // Logs out the user
    $request->session()->invalidate(); // Invalidate session
    $request->session()->regenerateToken(); // Regenerate CSRF token

    return redirect('/'); // Redirect to login page
}



//
    public function store(Request $request)
{
    // 1️⃣ Login via API
    $loginResponse = Http::post('https://sas-ecrt.onrender.com/api/login/', [
        'name' => $request->name,
        'password' => $request->pass,
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
    $studentResponse = Http::get('http://localhost:3000/api/students/getAll');

    if ($studentResponse->failed()) {
        return back()->with('error', 'Backend error: ' . $studentResponse->body());
    }

    $students = $studentResponse->json(); // ✅ decode JSON to array
// dd($students);
    // 3️⃣ Pass data to Blade view
    return view('dashboard.index', compact('students'));
}










}