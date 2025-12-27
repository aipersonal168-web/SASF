<!-- iziToast CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

<!-- Bootstrap 5 CSS -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

<!-- Modal -->

<div class="modal-content" id="view_modal">
    <!-- AJAX content will be injected here -->
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- jQuery (for AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 JS (with Popper included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- iziToast JS -->
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>



<!-- Session Notifications -->
<script>
    @if(session('success'))
    iziToast.success({
        title: 'Success',
        message: '{{ session('success') }}',
        position: 'topRight',
        timeout: 5000
    });
@endif

@if(session('error'))
    iziToast.error({
        title: 'Error',
        message: '{{ session('error') }}',
        position: 'topRight',
        timeout: 5000
    });
@endif
</script>

<!-- AJAX Function to View Student -->
<script>
    function view(route) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: route,
        dataType: 'json',
        success: function(results) {
            if (results.html) {
                // Inject HTML into modal content
                $('#view_modal').html(results.html);

                // Show the modal
                const modalEl = document.getElementById('exampleModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                // Bind form submission inside modal if it exists
                $('#createUserForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    saveUser(this, modal);
                });
            } else {
                iziToast.error({
                    title: 'Error',
                    message: 'No content returned',
                    position: 'topRight'
                });
            }
        },
        error: function(xhr) {
            iziToast.error({
                title: 'Error',
                message: 'Could not load form',
                position: 'topRight'
            });
        }
    });
}

 function edit(route) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: route,
        dataType: 'json',
        success: function(results) {
            if (results.html) {
                // Inject HTML into modal content
                $('#view_modal').html(results.html);

                // Show the modal
                const modalEl = document.getElementById('exampleModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                // Bind form submission inside modal if it exists
                $('#createUserForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    saveUser(this, modal);
                });
            } else {
                iziToast.error({
                    title: 'Error',
                    message: 'No content returned',
                    position: 'topRight'
                });
            }
        },
        error: function(xhr) {
            iziToast.error({
                title: 'Error',
                message: 'Could not load form',
                position: 'topRight'
            });
        }
    });
}


//Function updateStudent

function updateStudent(studentId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: `/students/update/${studentId}`,
        type: 'POST', // Use POST + _method for PUT
        dataType: 'json',
        data: {
            _method: 'PUT', // Laravel requires this for PUT in forms/AJAX
            student_id: $("#student_id").val(),
            student_name: $("#student_name").val(),
            student_room: $("#student_room").val(),
            student_shift: $("#student_shift").val()
        },
        success: function(data) {
            if (data.success) {
                iziToast.success({
                    title: 'Success',
                    message: data.message || 'Student updated successfully.',
                    position: 'topRight',
                    timeout: 5000
                });

                // Hide modal correctly
                const modalEl = document.getElementById('exampleModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();

                // Optional: update table row dynamically
                // updateTableRow(studentId, data.data);
            } else {
                iziToast.error({
                    title: 'Error',
                    message: data.message || 'Update failed.',
                    position: 'topRight'
                });
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let messages = Object.values(errors).flat().join("\n");
                iziToast.error({
                    title: 'Validation Error',
                    message: messages,
                    position: 'topRight'
                });
            } else if (xhr.status === 419) {
                iziToast.error({
                    title: 'Error',
                    message: 'CSRF token mismatch. Please refresh the page.',
                    position: 'topRight'
                });
            } else {
                iziToast.error({
                    title: 'Error',
                    message: 'An unexpected error occurred',
                    position: 'topRight'
                });
            }
        }
    });
}

// function delete student


function deleteRecord(route) {
    // Confirm deletion
    if (!confirm("Are you sure you want to delete this record?")) {
        return; // Stop if user cancels
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "DELETE",
        url: route,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                iziToast.success({
                    title: 'Deleted',
                    message: response.message || 'Record deleted successfully',
                    position: 'topRight',
                    timeout: 2000

                });
                setTimeout(() => {
                    
                    location.reload(); // Reload the page to reflect changes
                }, 2000);
            } else {
                iziToast.error({
                    title: 'Error',
                    message: response.message || 'Could not delete record',
                    position: 'topRight'
                });
            }
        },
        error: function(xhr) {
            iziToast.error({
                title: 'Error',
                message: 'An error occurred while deleting',
                position: 'topRight'
            });
        }
    });
}



function autoSearch() {
    
    var year = $('#year').val();
    var semester = $('#semester').val();
    var shift = $('#shift').val();
    var room = $('#room').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        url: '{{ route("attendance.search") }}',
        type: 'GET',
        data: {
            year: year,
            semester: semester,
            shift: shift,
            room: room
        },
        success: function(response) {
            $('.attendance-table tbody').html(response);
        },
        error: function(xhr) {
            iziToast.error({
                title: 'Error',
                message: xhr.responseText,
                position: 'topRight'
            });
        }
    });
}


// Function to handle semester change
function onSemesterChange() {
 
    const select = document.getElementById('semester');
    const firstOption = select.options[0];

    if (select.value !== '') {
        firstOption.style.display = 'none';
    }
}


</script>



<script>
    function storeAttendance() {

    // 🔹 Get values
    const yearId     = $('#year').val();
    const semesterId = $('#semester').val();
    const shiftId    = $('#shift').val();
    const roomId     = $('#room').val();
    const week       = $('#week').val();
    const teacherName = $('#teacherName').val().trim();
    const subjectName = $('#subjectName').val().trim();
    // 🔹 Validation
    if (!yearId || !semesterId || !shiftId || !roomId || !week) {
        alert('សូមជ្រើសរើស Year / Semester / Shift / Room / Week / Gender');
        return;
    }

    if (!teacherName) {
        alert('សូមបញ្ចូលឈ្មោះគ្រូ');
        return;
    }


    let attendance = [];

    $('.attendance-row').each(function () {
        attendance.push({
            student_id: $(this).find('.student-id').text().trim(),
            student_name: $(this).find('.student-name').text().trim(),
            status: $(this).find('.status').val(),
            gender: $(this).find('.student-gender').text().trim() === 'ប្រុស' ? 'M' : 'F',
            teacher_name: teacherName,
            subjectName : subjectName,
        });
    });
    if (attendance.length === 0) {
        alert('No students found');
        return;
    }


    // 🔹 Payload
    const payload = {
        year_id: yearId,
        semester_id: semesterId,
        shift_id: shiftId,
        room_id: roomId,
        week: week,
        attendance: attendance
    };


    // 🔹 AJAX POST
    $.ajax({
        url: '/attendance/store',
        type: 'POST',
        data: JSON.stringify(payload),
        contentType: 'application/json',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
         success: function(response) {
            $('.attendance-table tbody').html(response);
            iziToast.success({
                title: 'Success',
                message: 'Attendance saved successfully.',
                position: 'topRight',
                timeout: 5000
            });
        },
        error: function(xhr) {
            iziToast.error({
                title: 'Error',
                message: xhr.responseText,
                position: 'topRight'
            });
        }
    });
}
</script>