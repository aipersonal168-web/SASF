<?php

namespace App\Http\Controllers;
use App\Helpers\NumberHelper;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
class AttendanceController extends Controller
{
    public function indeqx() {
            try {
                $client = new Client();

                // Years
                $years = json_decode(
                    $client->get("http://localhost:5000/api/years/getAll")->getBody(),
                    true
                );
                $datas = array_values($years['data']);

                //       // Convert to Khmer
                // $data = collect($dataYears)->map(fn ($item) => [
                //     'id'   => $item['id'],
                //     'name' => NumberHelper::toKhmer($item['name']),
                // ])->toArray();
                
                // Semesters
                $semester = json_decode(
                    $client->get("http://localhost:5000/api/semesters/getAll")->getBody(),
                    true
                );
                $semesters = array_values($semester['data']);

            

                // Shifts
                $shift = json_decode(
                    $client->get("http://localhost:5000/api/shifts/getAll")->getBody(),
                    true
                );
                $shifts = array_values($shift['data']);


                // Rooms (LOWERCASE URL)
                $rooms = json_decode(
                    $client->get("http://localhost:5000/api/rooms/getAll")->getBody(),
                    true
                );
                $rooms = array_values($rooms['data']);
                // Convert to Khmer
            //    dd($rooms);

                    // dd($rooms);
                return view(
                    'dashboard.attendance.index',
                    compact('rooms', 'shifts', 'semesters', 'datas')
                );

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    
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
            $apiUrl  = $baseUrl . '/api/years/getAll';

            // Call API
            $response = $client->get($apiUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            // Decode JSON
            $years = json_decode($response->getBody()->getContents(), true);

            // Get result safely
            $datas = $years['data'] ?? [];
            // Use app.url from 
          
            // get api  semester
            $apiUrl  = $baseUrl . '/api/semesters/getAll';

            // Call API
            $response = $client->get($apiUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            // Decode JSON
            $semester = json_decode($response->getBody()->getContents(), true);

            // Get result safely
            $semesters = $semester['data'] ?? [];
            
            // get api  sgift
            $apiUrl  = $baseUrl . '/api/shifts/getAll';

            // Call API
            $response = $client->get($apiUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            // Decode JSON
            $shift = json_decode($response->getBody()->getContents(), true);

            // Get result safely
            $shifts = $shift['data'] ?? [];
            
            // get api  Room
            $apiUrl  = $baseUrl . '/api/rooms/getAll';

            // Call API
            $response = $client->get($apiUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            // Decode JSON
            $room = json_decode($response->getBody()->getContents(), true);

            // Get result safely
            $rooms = $room['data'] ?? [];

            
            // Return view
            return view('dashboard.attendance.index', compact('semesters', 'datas','shifts','rooms'));

        } catch (\Throwable $e) {
            // If API fails, still load page
            return view('dashboard.notification.index', [
                'data'  => [],
                'error' => $e->getMessage(),
            ]);
        }
    }




        
public function search(Request $request)
{

    // dd($request->all());
    // ✅ AJAX-friendly validation
    $validator = Validator::make($request->all(), [
        'year'      => 'required|integer',
        'semester'  => 'required|integer',
        'shift'     => 'required|integer',
        'room'      => 'required|integer',
    ], [
        'year.required'     => 'សូមជ្រើសរើសឆ្នាំសិក្សា',
        'semester.required' => 'សូមជ្រើសរើសឆមាស',
        'shift.required'    => 'សូមជ្រើសរើសវេន',
        'room.required'     => 'សូមជ្រើសរើសបន្ទប់',
    ]);

    if ($validator->fails()) {
        return response(
            '<tr>
                <td colspan="6" class="text-danger text-center">'
                . $validator->errors()->first() .
            '</td>
            </tr>',
            422
        );
    }

    try {
        $client = new Client();
        $response = $client->get(
            'http://localhost:5000/api/class/searchData',
            [
                'query' => [
                    'yearId'     => (int) $request->year,
                    'semesterId' => (int) $request->semester,
                    'shiftId'    => (int) $request->shift,
                    'roomId'     => (int) $request->room,
                ]
            ]
        );

        $result = json_decode($response->getBody(), true);

        // ✅ Handle API structure safely
        $attendanceData = $result['data'] ?? $result ?? [];
        // dd($attendanceData);
        // ❌ No data
        if (empty($attendanceData)) {
            return response(
                '<tr>
                    <td colspan="6" class="text-warning text-center">
                        មិនមានទិន្នន័យ
                    </td>
                </tr>'
            );
        }

        // ✅ Return ONLY table rows (AJAX)
        return view(
            'dashboard.attendance.table-rows',
            compact('attendanceData')
        );

    } catch (\Exception $e) {
        return response(
            '<tr>
                <td colspan="6" class="text-danger text-center">
                    មានបញ្ហាក្នុងការទាញយកទិន្នន័យ
                </td>
            </tr>',
            500
        );
    }
}




public function store(Request $request)
{
    try {
        // dd($request->all());
        // (Optional) Validate only top-level fields
        $request->validate([
            'year_id'     => 'required|integer',
            'semester_id' => 'required|integer',
            'shift_id'    => 'required|integer',
            'room_id'     => 'required|integer',
            'week'        => 'required|integer',
            'attendance'  => 'required|array|min:1',
        ]);

        // 🔹 Send EXACT data to Node.js
        $client = new Client();

        $response = $client->post(
            'http://localhost:5000/api/attendance/store',
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => $request->all()
            ]
        );

        $result = json_decode($response->getBody(), true);
    
        // ✅ Handle API structure safely
       return response()->json([
                'success' => true,
                'message' => 'Student add successfully.'
            ]);

    } catch (\Throwable $e) {
        return back()->with('error', $e->getMessage());
    }
}




}

  