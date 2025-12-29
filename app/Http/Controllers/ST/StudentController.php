<?php

namespace App\Http\Controllers\ST;
use App\Helpers\NumberHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;


class StudentController extends Controller
{
    /**
     * Display a listing of students with pagination.
     */


public function index(Request $request)
{
    try {
        // Create HTTP client
        $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // DEV ONLY
        ]);

        // Use app.url from config
        $baseUrl = config('app.url'); 
        $apiUrl  = $baseUrl . '/api/students/getAll';

        // Call API
        $response = $client->get($apiUrl, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        // Decode JSON response
        $studentsArray = json_decode($response->getBody()->getContents(), true);

        // Pagination setup
        $perPage = 10;
        $currentPage = $request->input('page', 1);

        $studentsCollection = collect($studentsArray);
        $currentPageItems = $studentsCollection
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $students = new LengthAwarePaginator(
            $currentPageItems,
            $studentsCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        return view('dashboard.students.index', compact('students'));

    } catch (\Exception $e) {
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

    /**
     * Store a newly created student.
     */



public function store(Request $request)
{
    // Validate input
    $request->validate([
        'student_id'   => 'required',
        'student_name' => 'required',
        'gender'       => 'required',
    ]);

    try {
        $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // DEV ONLY
        ]);

        // Use app.url from config
        $baseUrl = config('app.url');  
        $apiUrl  = $baseUrl . '/api/students/create';

        // Call API
        $response = $client->request('POST', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
            'json'    => [
                'student_id'   => $request->student_id,
                'student_name' => $request->student_name,
                'gender'       => $request->gender,
            ],
        ]);

        // Decode response
        $responseData = json_decode($response->getBody()->getContents(), true);
        // dd($responseData);
        // You can check status code if needed
        if ($response->getStatusCode() >= 400) {
            return back()->with('error', 'Backend error: ' . json_encode($responseData));
        }

        return back()->with('success', 'Student added successfully.');

    } catch (\Exception $e) {
        dd($e);
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
    /**
     * Show student details for AJAX modal.
     */
    public function show($id)
    {
        try {
           

            $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // DEV ONLY
        ]);

        // Use app.url from config
        $baseUrl = config('app.url');  
        $apiUrl  = $baseUrl . '/api/students/getby/'.$id;

         $response = $client->request('GET', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
        ]);

            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch student.'
                ], 500);
            }

            $student = json_decode($response->getBody(), true);

            return response()->json([
                'html' => view('dashboard.students.view', compact('student'))->render()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to load student.'
            ], 500);
        }
    }

    /**
     * Load student data for edit modal via AJAX.
     */
    public function edit($id)
    {
        try {
             $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // DEV ONLY
        ]);

        // Use app.url from config
        $baseUrl = config('app.url');  
        $apiUrl  = $baseUrl . '/api/students/getby/'.$id;

         $response = $client->request('GET', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
        ]);


            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch student.'
                ], 500);
            }

            $student = json_decode($response->getBody(), true);

            return response()->json([
                'html' => view('dashboard.students.edit', compact('student'))->render()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to load student.'
            ], 500);
        }
    }

    /**
     * Update the specified student via API.
     */


public function update(Request $request, $id)
{
    $request->validate([
        'student_id'   => 'required',
        'student_name' => 'required',
        'gender'       => 'required',
    ], [
        'student_id.required'   => 'Student ID is required.',
        'student_name.required' => 'Student name is required.',
        'gender.required'       => 'Student gender is required.',
    ]);

    try {
        $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false, // DEV ONLY
        ]);

        // Use app.url from config
        $baseUrl = config('app.url');  
        $apiUrl  = $baseUrl . '/api/students/update/' . $id;

        // Call API
        $response = $client->request('PUT', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
            'json'    => [
                'student_id'   => $request->student_id,
                'student_name' => $request->student_name,
                'gender'       => $request->gender,
            ],
        ]);

        // Decode response
        $statusCode   = $response->getStatusCode();
        $responseData = json_decode($response->getBody()->getContents(), true);

        if ($statusCode !== 200) {
            return response()->json([
                'success' => false,
                'message' => 'Backend error',
                'data'    => $responseData
            ], $statusCode);
        }

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully.',
            'data'    => $responseData
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified student via API.
     */






public function destroy($id)
{
    try {
        $client = new Client([
            'cookies' => true,
            'timeout' => 10,
            'verify'  => false,
        ]);

        $baseUrl = config('app.url');
        $apiUrl = $baseUrl . '/api/students/distroy/' . $id;

        $response = $client->request('DELETE', $apiUrl, [
            'headers' => ['Accept' => 'application/json'],
        ]);

        $statusCode = $response->getStatusCode();
        $responseData = json_decode($response->getBody()->getContents(), true);

        if ($statusCode !== 200) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student.',
                'data'    => $responseData
            ], $statusCode);
        }

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully.',
            'data'    => $responseData
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}


  


}


                    
        