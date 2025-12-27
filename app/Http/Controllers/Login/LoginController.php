<?php

namespace App\Http\Controllers\Login;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Use the Http facade
use Illuminate\Support\Facades\Session;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Log;
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


public function store(Request $request)
{
    try {
        // dd($request->all());
        // 1️⃣ Login via API
            $loginResponse = Http::withoutVerifying()->post(
            'https://sas-ecrt.onrender.com/api/login',
            [
                'name'     => $request->name,
                'password' => $request->pass,
                'role'     => $request->role,
            ]
        );

        if ($loginResponse->failed()) {
            return back()->withErrors([
                'login' => 'Login failed! Invalid username or password.'
            ]);
        }

        // Save user session
        $user = $loginResponse->json();
        Session::put('user', $user);

        // 2️⃣ Fetch students from API
        $studentResponse = Http::withoutVerifying()->get(
            'https://sas-ecrt.onrender.com/api/students/getAll'
        );

        if ($studentResponse->failed()) {
            return back()->with('error', 'Backend error: ' . $studentResponse->body());
        }

        $students = $studentResponse->json();

        // 3️⃣ Show dashboard when login success
        // return view('dashboard.index', compact('students'));
         // ✅ Return JSON with redirect URL
        return response()->json([
    'success'  => true,
    'message'  => 'Login successful!',
    'redirect' => route('dashboard') // your dashboard route
]);

    } catch (\Exception $e) {
        Log::error('API error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return back()->with('error', 'Server error: please try again later.');
    }
}







}