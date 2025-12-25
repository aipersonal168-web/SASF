<?php

namespace App\Http\Controllers\ST;
use App\Helpers\NumberHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentController extends Controller
{
    /**
     * Display a listing of students with pagination.
     */
    public function index(Request $request)
    {
        try {
            $response = Http::get('http://localhost:5000/api/students/getAll');

            if ($response->failed()) {
                return back()->with('error', 'Backend error: ' . $response->body());
            }

            $studentsArray = $response->json();
            $perPage = 10;
            $currentPage = $request->input('page', 1);

            $studentsCollection = collect($studentsArray);
            $currentPageItems = $studentsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

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
            // dd($students);
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

        // dd($request->all());
        $request->validate([
            'student_id' => 'required',
            'student_name' => 'required',
            'gender' => 'required',
        ]);

        try {
            $response = Http::post('http://localhost:5000/api/students/create', $request->only([
                'student_id', 'student_name','gender'
            ]));

            if ($response->failed()) {
                return back()->with('error', 'Backend error: ' . $response->body());
            }

            return back()->with('success', 'Student added successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show student details for AJAX modal.
     */
    public function show($id)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', "http://localhost:5000/api/students/getby/$id");

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
            $client = new Client();
            $response = $client->request('GET', "http://localhost:5000/api/students/getby/$id");

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
            'student_id' => 'required',
            'student_name' => 'required',
            'gender' => 'required',
        ], [
            'student_id.required' => 'Student ID is required.',
            'student_name.required' => 'Student name is required.',
            'gender.required' => 'Student gender is required.',
           
        ]);

        try {
            $response = Http::put("http://localhost:5000/api/students/update/$id", $request->only([
                'student_id', 'student_name', 'gender',
            ]));
            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backend error: ' . $response->body()
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully.',
                'data' => $response->json()
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


// dd($id);
        try {

            $response = Http::delete("http://localhost:5000/api/students/distroy/$id");

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete student.'
                ], 500);
            }
            // dd($response->body());
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


  


}


                    
        