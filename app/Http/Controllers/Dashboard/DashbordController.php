<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashbordController extends Controller
{
    //




// public function index(Request $request)
// {
//    dd("90");

//     try {
//         // ✅ 2️⃣ Guzzle Client
//         $client = new Client([
//             'cookies' => true,
//             'timeout' => 10,
//             'verify'  => false, // 🔥 FIX SSL ERROR (DEV ONLY)
//         ]);

//         // ✅ 3️⃣ API URL
//         $host = config('app.url');
//         $url  = $host . '/api/students/getAll';

//         // ✅ 4️⃣ POST Login Request
//         $response = $client->request('GET', $url, [
//             'headers' => [
//                 'Accept' => 'application/json',
//             ],
//         ]);

//         // ✅ 5️⃣ Decode Response
//         $data = json_decode($response->getBody(), true);
//         dd($data);

//         // ❌ API-level login failure
//         if (isset($data['success']) && $data['success'] === false) {
//             return response()->json([
//                 'success' => false,
//                 'message' => $data['message'] ?? 'Login failed',
//             ], 401);
//         }

//         // ✅ 6️⃣ Save session
//         Session::put('user', $data['user'] ?? $data);
//         Session::put('token', $data['token'] ?? null);

//         // ✅ 7️⃣ Success
//         return response()->json([
//             'success'  => true,
//             'message'  => 'Login successful!',
//             'redirect' => route('dashboard'),
//         ]);

//     } catch (\GuzzleHttp\Exception\ClientException $e) {
//         // ❌ 401 / 422
//         return response()->json([
//             'success' => false,
//             'message' => 'Invalid username or password',
//         ], 401);

//     } catch (\Exception $e) {
//         Log::error('Login API Error: ' . $e->getMessage());

//         return response()->json([
//             'success' => false,
//             'message' => 'Server error, please try again later',
//         ], 500);
//     }
// }


}