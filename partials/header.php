<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trello Native PHP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/trello_native_php/public/css/custom.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/trello_native_php/home.php">Trello Native</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/trello_native_php/home.php">Home</a></li>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">Hi, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Guest'); ?></span>
        <?php if(isset($_SESSION['user_id'])): ?>
          <span class="badge bg-info text-dark ms-2">Role: <?php echo htmlspecialchars($_SESSION['role'] ?? 'user'); ?></span>
          <a href="/trello_native_php/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<div class="container-fluid py-3">
