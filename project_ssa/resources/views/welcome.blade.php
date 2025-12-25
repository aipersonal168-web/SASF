<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign In</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
    @if ($errors->has('login'))
    <div class="alert alert-danger">
      {{ $errors->first('login') }}
    </div>
    @endif
    <h3 class="text-center fst-italic">Sign In [SAS]</h3>

    <!-- Use route('login') or your controller route -->
    <form action="{{ route('login') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label">User Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
          autocomplete="username" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password"
          autocomplete="current-password" required>
      </div>

      <div class="row">
        <div class="col">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="role" id="radioDefault1" value="user">
            <label class="form-check-label" for="radioDefault1">General User</label>
          </div>
        </div>
        <div class="col">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="role" id="radioDefault2" value="admin" checked>
            <label class="form-check-label" for="radioDefault2">Admin System</label>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100 my-3">Sign In</button>
    </form>
  </div>
</body>

</html>