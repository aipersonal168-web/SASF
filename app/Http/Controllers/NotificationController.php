<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
class NotificationController extends Controller
{
    //

    public function index() {
            try {
                $client = new Client();

                // datagbfs
                $datagbfs = json_decode(
                    $client->get("http://localhost:5000/gbfs/predict")->getBody(),
                    true
                );
                $data = $datagbfs['result'];
                // dd($data);
                return view(
                    'dashboard.notification.index',
                    compact('data')
                );

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }

}