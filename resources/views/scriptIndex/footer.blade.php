<!-- iziToast CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

<!-- Bootstrap 5 CSS -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Modal -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- iziToast JS -->
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>



<!-- jQuery (for AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 JS (with Popper included) -->


<!-- iziToast JS -->
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>



<script>
    function loginStudent() {
    var roles = $("input[name='role']:checked").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('login.store') }}", // Laravel route
        type: 'POST',
        dataType: 'json',
        data: {
            name: $("#name").val(),
            pass: $("#password").val(),
            role: roles
        },
        success: function (data) {
           if (data.success) {
        iziToast.success({
            title: 'Success',
            message: data.message,
            position: 'topRight'
        });

        // Redirect to dashboard
        window.location.href = data.redirect;

            } else {
                iziToast.error({
                    title: 'Error',
                    message: data.message,
                    position: 'topRight'
                });
            }
        },
        error: function (xhr) {
            let message = 'An unexpected error occurred';
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                message = Object.values(errors).flat().join("\n");
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            iziToast.error({
                title: 'Error',
                message: message,
                position: 'topRight'
            });
        }
    });
}
</script>