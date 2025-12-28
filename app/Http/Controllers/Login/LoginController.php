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
    // ✅ 1️⃣ Validation
    $request->validate([
        'name' => 'required',
        'pass' => 'required',
        'role' => 'required',
    ], [
        'name.required' => 'Username is required',
        'pass.required' => 'Password is required',
        'role.required' => 'Role is required',
    ]);

    try {
        // ✅ 2️⃣ Guzzle Client
        $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // 🔥 FIX SSL ERROR (DEV ONLY)
        ]);

        // ✅ 3️⃣ API URL
        $host = config('app.url');
        $url  = $host . '/api/login';

        // ✅ 4️⃣ POST Login Request
        $response = $client->request('POST', $url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'json' => [
                'name'     => $request->name,
                'pass' => $request->pass, // ✅ FIX
                'role'     => $request->role,
            ],
        ]);

        // ✅ 5️⃣ Decode Response
        $data = json_decode($response->getBody(), true);
        // dd($data);

        // ❌ API-level login failure
        if (isset($data['success']) && $data['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $data['message'] ?? 'Login failed',
            ], 401);
        }

        // ✅ 6️⃣ Save session
        Session::put('user', $data['user'] ?? $data);
        Session::put('token', $data['token'] ?? null);

        // ✅ 7️⃣ Success
        return response()->json([
            'success'  => true,
            'message'  => 'Login successful!',
            'redirect' => route('dashboard'),
        ]);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        // ❌ 401 / 422
        return response()->json([
            'success' => false,
            'message' => 'Invalid username or password',
        ], 401);

    } catch (\Exception $e) {
        Log::error('Login API Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Server error, please try again later',
        ], 500);
    }
}







}