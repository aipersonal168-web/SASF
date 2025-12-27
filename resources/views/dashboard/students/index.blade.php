@extends('layouts.app')

@section('title', 'Students')
@section('menu-students', 'active')

@section('content')

    <h2 style="margin-bottom: 20px">Students</h2>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCreate"
        data-bs-whatever="@mdo">+ Add New Student</button>

    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal"
        onclick="Searchstuden()">
        + Add Students
    </button>


    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #007bff;
            text-decoration: none;
            margin-right: 8px;
        }

        a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #c82333;
        }
    </style>

    <div>
        @include('dashboard.students.table')
    </div>
    {{-- Modal --}}
    <div class=" modal fade" id="exampleModalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">New Students</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('students.store') }}" method="POST" id="studentForm">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-6">
                                    <label class="col-form-label">Enter_ID:</label>
                                    <input type="text" class="form-control" name="student_id">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-6">
                                    <label class="col-form-label">Enter_Name:</label>
                                    <input type="text" class="form-control" name="student_name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-6">
                                    <label class="col-form-label">Gender:</label>
                                    <select class="form-control" name="gender">
                                        <option value="">ជ្រើសរើសភេទ</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    {{-- Submit form --}}
                    <button class="btn btn-primary" onclick="document.getElementById('studentForm').submit();">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- endmodal --}}



    @include('dashboard.footer')

    {{-- @include('dashboard.students.view') --}}
@endsection

<!-- In your Blade template (e.g., resources/views/students.blade.php) -->
