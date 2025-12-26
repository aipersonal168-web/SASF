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
    $messages = [
        'name.required'     => 'Please enter your username.',
        'password.required' => 'Password is required to log in.',
        'role.required'     => 'Please select a role.',
    ];

    $validated = $request->validate([
        'name'     => 'required',
        'password' => 'required',
        'role'     => 'required',
    ], $messages);

    // 1. Prepare Guzzle Client
    $client = new \GuzzleHttp\Client(['cookies' => true]);

    // Use a proper login endpoint
    $url = config('app.url') . '/api/users';
// dd($url);
    try {
        // 2. Prepare login data
        $datalogin = [
            'name'     => $request->name,
            'password' => $request->password,
            'role'     => $request->role,
        ];

        // 3. Send POST Request to API
        $response = $client->post($url, [
            'headers' => ['Accept' => 'application/json'],
            'json'    => $datalogin,
        ]);
        dd($response);
        // 4. Decode Response and Handle Success
        $apiData = json_decode($response->getBody(), true);
// dd($apiData);
        if (!empty($apiData['token'])) {
            // Store token in session if needed
            session(['api_token' => $apiData['token']]);

            return redirect()->route('dashboard.index')->with('success', 'Login successful!');
        }

        return back()->withInput()->withErrors(['login' => 'Login failed. No token received.']);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $errorMessage = json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? 'Invalid credentials.';
        return back()->withInput()->withErrors(['login' => $errorMessage]);

    } catch (\Exception $e) {
        return back()->withInput()->withErrors(['login' => 'Could not connect to the authentication server.']);
    }
}
public function logout(Request $request)
{
    Auth::logout(); // Logs out the user
    $request->session()->invalidate(); // Invalidate session
    $request->session()->regenerateToken(); // Regenerate CSRF token

    return redirect('/'); // Redirect to login page
}
}