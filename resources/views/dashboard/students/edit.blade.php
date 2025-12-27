<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Student Details</h5>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Enter ID:</label>
                        <input type="text" id="student_id" class="form-control bg-light text-dark"
                            value="{{ $student['student_id'] ?? '' }}" readonly>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Enter Name:</label>
                        <input type="text" id="student_name" class="form-control"
                            value="{{ $student['student_name'] ?? '' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Enter Name:</label>
                        <input type="text" id="gender" class="form-control"
                            value="{{ $student['gender'] ?? '' }}">
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                <button type="button" class="btn btn-primary"
                    onclick="updateStudent({{ $student['id'] }})">Update</button>

            </div>

        </div>
    </div>
</div>