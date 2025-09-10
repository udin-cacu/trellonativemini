<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header("Location: /trello_native_php/home.php");
  exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Trello Native PHP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow rounded-4">
          <div class="card-body p-4">
            <h4 class="mb-3 text-center">Login</h4>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" id="email" class="form-control" value="admin@example.com">
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" id="password" class="form-control" value="admin123">
            </div>
            <button class="btn btn-primary w-100" onclick="doLogin()">Login</button>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
function doLogin(){
  $.post('login.php', {email: $('#email').val(), password: $('#password').val()}, function(res){
    if(res.ok){ window.location='home.php'; }
    else Swal.fire({icon:'error', title: res.msg || 'Login gagal'});
  }, 'json');
}
</script>
</body>
</html>
