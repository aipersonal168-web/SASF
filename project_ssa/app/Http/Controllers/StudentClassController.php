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

        $client = new Client();

            // Years
            $years = json_decode(
                $client->get("http://localhost:5000/api/years/getAll")->getBody(),
                true
            );
            $dataYears = array_values($years['data']);

                  // Convert to Khmer
            $datas = collect($dataYears)->map(fn ($item) => [
                'id'   => $item['id'],
                'name' => NumberHelper::toKhmer($item['name']),
            ])->toArray();
            
            // Semesters
            $semester = json_decode(
                $client->get("http://localhost:5000/api/semesters/getAll")->getBody(),
                true
            );
            $semestersdata = array_values($semester['data']);

             // Convert to Khmer
            $semesters = collect($semestersdata)->map(fn ($item) => [
                'id'   => $item['id'],
                'name' => NumberHelper::toKhmer($item['name']),
            ])->toArray();

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
        // dd($request);
        $totalInput = (int) $request->total_students;

        if ($totalInput < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid total_students value'
            ], 422);
        }

        // 1️⃣ Get students from Node.js API
        $response = Http::get('http://localhost:5000/api/students/getAll');

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot connect to student API',
                'status' => $response->status()
            ], 500);
        }

        $students = $response->json();
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
                 // match Node.js field
                'st_id' => $students[$i]['id'], // match Node.js field
                'student_id' => $students[$i]['student_id'], // match Node.js field
                'student_name' => $students[$i]['student_name'],
                'gender' => $students[$i]['gender'],
                'year_id'      => (int) $request->year_id,
                'semester_id'  => (int) $request->semester_id,
                'shift_id'     => (int) $request->shift_id,
                'room_id'      => (int) $request->room_id,
            ];
        }
        // dd($insertData);
        // 4️⃣ Send to Node.js API as JSON
        $storeResponse = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://localhost:5000/api/class/storeData', [
            'students' => $insertData
        ]);

        // 5️⃣ Check response
        if ($storeResponse->failed()) {
            return response()->json([
                'success' => false,
                'message' => $storeResponse->json()['message'] ?? 'Insert failed in attendance API',
                'status' => $storeResponse->status(),
                'body' => $storeResponse->body()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully inserted {$totalInput} students",
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