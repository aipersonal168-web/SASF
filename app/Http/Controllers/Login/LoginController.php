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

    $client = new \GuzzleHttp\Client(['cookies' => true]);

    // Point directly to your Render API backend
    $url = 'https://sas-ecrt.onrender.com/api/users';

    try {
        $datalogin = [
            'name'     => $request->name,
            'password' => $request->password,
            'role'     => $request->role,
        ];

        $response = $client->post($url, [
            'headers' => ['Accept' => 'application/json'],
            'json'    => $datalogin,
        ]);

        $apiData = json_decode($response->getBody(), true);
        dd($apiData);

        if (!empty($apiData['token'])) {
            session(['api_token' => $apiData['token']]);
            return redirect()->route('dashboard.index')->with('success', 'Login successful!');
        }

        return back()->withInput()->withErrors(['login' => 'Login failed. No token received.']);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        dd($e);
        $errorMessage = json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? 'Invalid credentials.';
        return back()->withInput()->withErrors(['login' => $errorMessage]);

    } catch (\Exception $e) {
        dd($e);
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