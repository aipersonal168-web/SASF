<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light text-center">
            <tr>
                <th class="col text-center">N</th>
                <th class="col text-center">ID</th>
                <th class="col text-center">Name</th>
                <th class="col text-center">gender</th>
                <th class="col text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr class="text-center">

                <td class="text-center">{{ $students->firstItem() + $loop->index }}</td>


                <td>{{ $student['student_id'] }}</td>
                <td>{{ $student['student_name'] }}</td>
                <td>{{ $student['gender'] }}</td>
                <td>
                    <button type="button" onclick="view('{{ route('students.view', $student['id']) }}')"
                        class="btn btn-primary btn-sm me-1">
                        <i class="fa fa-eye me-1"></i> View
                    </button>
                    <button type="button" onclick="edit('{{ route('students.edit', $student['id']) }}')"
                        class="btn btn-warning btn-sm me-1">
                        <i class="fa fa-edit me-1"></i> Edit
                    </button>

                    <button type="button" onclick="deleteRecord('{{ route('students.destroy', $student['id']) }}')"
                        class="btn btn-danger btn-sm me-1">
                        <i class="fa fa-trash me-1"></i> Delete
                    </button>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No students found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $students->links('pagination::bootstrap-5') }}
</div>