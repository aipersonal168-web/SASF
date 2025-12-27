<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
 use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
     

    use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

public function login(Request $request)
{
    // ===============================
    // 1️⃣ DEBUG FORM INPUT (OPTIONAL)
    // ===============================
    dd($request->all());

    // 2️⃣ Validate input
    $request->validate([
        'name' => 'required',
        'pass' => 'required',
        'role' => 'required',
    ], [
        'name.required' => 'Please enter your username.',
        'pass.required' => 'Password is required to log in.',
        'role.required' => 'Please select a role.',
    ]);

    // 3️⃣ Create HTTP client
    $client = new Client([
        'timeout' => 10,
    ]);

    // 4️⃣ Node.js API URL
    $url = 'https://sas-ecrt.onrender.com/api/login';

    try {
        // 5️⃣ Send POST request
        $response = $client->post($url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'json' => [
                'name' => $request->name,
                'pass' => $request->pass,
                'role' => $request->role,
            ],
        ]);

        // ===============================
        // 6️⃣ DEBUG RAW RESPONSE (OPTIONAL)
        // ===============================
        // dd(
        //     $response->getStatusCode(),
        //     (string) $response->getBody()
        // );

        // 7️⃣ Decode response
        $apiData = json_decode($response->getBody()->getContents(), true);

        // ===============================
        // 8️⃣ DEBUG DECODED JSON (OPTIONAL)
        // ===============================
        dd($apiData);

        // 9️⃣ Login success
        if (!empty($apiData['token'])) {
            session([
                'api_token' => $apiData['token'],
            ]);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Login successful!');
        }

        // 🔟 Invalid credentials
        return back()->withErrors([
            'login' => $apiData['message'] ?? 'Invalid credentials.',
        ])->withInput();

    } catch (ClientException $e) {
        // ===============================
        // 1️⃣1️⃣ DEBUG API ERROR (OPTIONAL)
        // ===============================
        dd(
            $e->getResponse()->getStatusCode(),
            (string) $e->getResponse()->getBody()
        );

        $body = json_decode(
            $e->getResponse()->getBody()->getContents(),
            true
        );

        return back()->withErrors([
            'login' => $body['message'] ?? 'Invalid credentials.',
        ])->withInput();

    } catch (\Exception $e) {
        // ===============================
        // 1️⃣2️⃣ SERVER / CONNECTION ERROR
        // ===============================
        return back()->withErrors([
            'login' => 'Authentication server is unavailable.',
        ])->withInput();
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