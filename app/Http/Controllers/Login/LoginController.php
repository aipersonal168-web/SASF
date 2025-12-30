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
        $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // DEV ONLY
        ]);

        $host = config('app.url');

        // ✅ Login API
        $url  = $host . '/api/login';
        $response = $client->request('POST', $url, [
            'headers' => ['Accept' => 'application/json'],
            'json'    => [
                'name' => $request->name,
                'pass' => $request->pass,
                'role' => $request->role,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['success']) && $data['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $data['message'] ?? 'Login failed',
            ], 401);
        }

        // ✅ Get students
        $urlStudent = $host . '/api/students/getAll';
        $responseStudent = $client->request('GET', $urlStudent, [
            'headers' => ['Accept' => 'application/json'],
        ]);

        $students = json_decode($responseStudent->getBody(), true);
        $studentCount = count($students);
        // ✅ Get students notification
        $urlStudent = $host . '/api/gbfs/predict';
        $responseStudent = $client->request('GET', $urlStudent, [
            'headers' => ['Accept' => 'application/json'],
        ]);

        $gbfs = json_decode($responseStudent->getBody(), true);
        $gbfsCount = $gbfs['total'];
        // dd($gbfsCount);
        // ✅ Store in session
        Session::put('students', $students);
        Session::put('studentCount', $studentCount);
        Session::put('gbfsCount', $gbfsCount);
        Session::put('user', $data['user'] ?? $data);
        Session::put('token', $data['token'] ?? null);

        // ✅ Return JSON with redirect
        return response()->json([
            'success'  => true,
            'message'  => 'Login successful!',
            'redirect' => route('dashboard')
        ]);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        // Get raw response body
    $body = $e->getResponse()->getBody()->getContents();

    // Decode JSON
    $data = json_decode($body, true);

    return response()->json([
        'success' => false,
        'message' => $data['message'] ?? 'Login failed',
    ], $e->getCode()); // 401

    } catch (\Exception $e) {
        Log::error('Login API Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Server error, please try again later',
        ], 500);
    }
}








}