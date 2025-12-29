<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            // Create HTTP client
            $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // DEV ONLY
        ]);

            // Use app.url from config
            $baseUrl = config('app.url'); // from .env APP_URL
            $apiUrl  = $baseUrl . '/api/gbfs/predict';

            // Call API
            $response = $client->get($apiUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            // Decode JSON
            $datagbfs = json_decode($response->getBody()->getContents(), true);

            // Get result safely
            $data = $datagbfs['result'] ?? [];
            // Return view
            return view('dashboard.notification.index', compact('data'));

        } catch (\Throwable $e) {
            // If API fails, still load page
            return view('dashboard.notification.index', [
                'data'  => [],
                'error' => $e->getMessage(),
            ]);
        }
    }
}