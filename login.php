<?php
session_start();
header('Content-Type: application/json');
require __DIR__.'/config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['name'] = $user['name'];
  $_SESSION['role'] = $user['role'] ?? 'user';
  echo json_encode(['ok'=>true]);
} else {
  echo json_encode(['ok'=>false,'msg'=>'Email atau password salah']);
}
