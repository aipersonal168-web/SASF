<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header without close button -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Student Details</h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Enter ID:</label>
                        <input type="text" class="form-control bg-light text-dark" name="student_id"
                            value="{{ $student['student_id'] ?? '' }}" readonly>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Enter Name:</label>
                        <input type="text" class="form-control bg-light text-dark" name="student_name"
                            value="{{ $student['student_name'] ?? '' }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">gender:</label>
                        <input type="text" class="form-control bg-light text-dark" name="student_name"
                            value="{{ $student['gender'] ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Modal Footer with Close Button -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>