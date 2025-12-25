<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashbordController extends Controller
{
    //

 public function index()
{
    // Call Node.js API
    $response = Http::get('http://localhost:5000/api/students/count');

    // Convert response to array
    $data = $response->json();

    $countStudents = $data['total'];
    // dd($countStudents);
    return view('dashboard.index', compact('countStudents'));
}

}