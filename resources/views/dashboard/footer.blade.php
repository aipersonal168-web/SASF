<!-- iziToast CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

<!-- Bootstrap 5 CSS -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

<!-- Modal -->

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- iziToast JS -->
  <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

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
            gender: $("#gender").val(),
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

  function Searchstuden() {
    const route = "{{ route('class.searchData') }}";

    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
      type: "GET",
      url: route,
      dataType: 'json',
      success: function(results) {
        if (results.html) {
          // Inject ONLY inner content
          $('#view_modal').html(results.html);

          // Initialize modal on the OUTER wrapper
          const modalEl = document.getElementById('exampleModaladd');
          if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            // Bind form submit inside modal (delegated to ensure fresh binding)
            $('#createUserForm').off('submit').on('submit', function(e) {
              e.preventDefault();
              saveUser(this, modal);
            });
          } else {
            iziToast.error({
              title: 'Error',
              message: 'Modal element not found',
              position: 'topRight'
            });
          }
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


// add student new class

function addclassStudent() {
    // Debug alert

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/class/storeData',
        type: 'POST',
        dataType: 'json',
        data: {
            year_id: $("#year_id").val(),
            semester_id: $("#semester_id").val(),
            shift_id: $("#shift_id").val(),
            total_students: $("#total_students").val(),
            room_id: $("#room_id").val()
        },
        success: function (data) {
            if (data.success) {
                iziToast.success({
                    title: 'Success',
                    message: data.message || 'Students saved successfully.',
                    position: 'topRight',
                    timeout: 5000
                });

                // Hide modal
                const modalEl = document.getElementById('exampleModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();

                // Optional: update table dynamically
                // updateTableRow(data.data);
            } else {
                iziToast.error({
                    title: 'Error',
                    message: data.message || 'Save failed.',
                    position: 'topRight'
                });
            }
        },
        error: function (xhr) {
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
    
</script>