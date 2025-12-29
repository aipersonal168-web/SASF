<?php

namespace App\Http\Controllers;
use App\Helpers\NumberHelper;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
class StudentClassController extends Controller
{
    //
   public function createClassStudent(){
       try {

         $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false,
        ]);

        $baseUrl = config('app.url');
        $apiUrl = $baseUrl . '/api/years/getAll';

       $response = $client->request('GET', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
        ]);
       $years = json_decode($response->getBody()->getContents(), true);
        // Years
      $dataYears = array_values($years['data']);

                  // Convert to Khmer
            $datas = collect($dataYears)->map(fn ($item) => [
                'id'   => $item['id'],
                'name' => NumberHelper::toKhmer($item['name']),
            ])->toArray();
            
            // Semesters
            

            $apiUrl = $baseUrl . '/api/semesters/getAll';

       $response = $client->request('GET', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
        ]);
          $semester = json_decode($response->getBody()->getContents(), true);
          $semestersdata = array_values($semester['data']);

             // Convert to Khmer
            $semesters = collect($semestersdata)->map(fn ($item) => [
                'id'   => $item['id'],
                'name' => NumberHelper::toKhmer($item['name']),
            ])->toArray();

            // Shifts
            
              $apiUrl = $baseUrl . '/api/shifts/getAll';

       $response = $client->request('GET', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
        ]);
          $shift = json_decode($response->getBody()->getContents(), true);
            $shifts = array_values($shift['data']);

              

             $apiUrl = $baseUrl . '/api/rooms/getAll';

       $response = $client->request('GET', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
        ]);
          $room = json_decode($response->getBody()->getContents(), true);
            $rooms = array_values($room['data']);
             // Convert to Khmer

            return response()->json([
            'success' => true,
            'html' => view('dashboard.students.createclass', compact('datas', 'semesters', 'shifts','rooms'))->render(),
        ], 200);    

       } catch (\Exception $e) {
           return response()->json([
               'status' => 'error',
               'message' => $e->getMessage(),
           ], 500);
       }
     }






public function store(Request $request)
{
    try {
        $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // DEV ONLY
        ]);

        $totalInput = (int) $request->total_students;

        if ($totalInput < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid total_students value'
            ], 422);
        }

        // 1️⃣ Get students from Node.js API
        $baseUrl = config('app.url');
        $apiUrl  = $baseUrl . '/api/students/getAll';

        $response = $client->request('GET', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
        ]);

        // Decode JSON into array
        $students = json_decode($response->getBody()->getContents(), true);

        if (!is_array($students)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot connect to student API',
                'status'  => $response->getStatusCode()
            ], 500);
        }

        $totalApiStudents = count($students);

        // 2️⃣ Validate total input
        if ($totalInput > $totalApiStudents) {
            return response()->json([
                'success' => false,
                'message' => "You entered $totalInput students but only $totalApiStudents available"
            ], 422);
        }

        // 3️⃣ Prepare insert data
        $insertData = [];
        for ($i = 0; $i < $totalInput; $i++) {
            $insertData[] = [
                'st_id'        => $students[$i]['id'],
                'student_id'   => $students[$i]['student_id'],
                'student_name' => $students[$i]['student_name'],
                'gender'       => $students[$i]['gender'],
                'year_id'      => (int) $request->year_id,
                'semester_id'  => (int) $request->semester_id,
                'shift_id'     => (int) $request->shift_id,
                'room_id'      => (int) $request->room_id,
            ];
        }

        // 4️⃣ Send to Node.js API as JSON
        $apiUrl  = $baseUrl . '/api/class/storeData';
        $storeResponse = $client->request('POST', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
            'json'    => ['students' => $insertData],
        ]);

        $storeData = json_decode($storeResponse->getBody()->getContents(), true);

        // 5️⃣ Check response
        if ($storeResponse->getStatusCode() !== 200) {
            return response()->json([
                'success' => false,
                'message' => $storeData['message'] ?? 'Insert failed in attendance API',
                'status'  => $storeResponse->getStatusCode(),
                'body'    => $storeData
            ], 500);
        }

        // ✅ Success alert
        return response()->json([
            'success'  => true,
            'message'  => "Successfully inserted {$totalInput} students",
            'inserted' => $insertData
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}



}