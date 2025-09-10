<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['ok'=>false, 'msg'=>'Unauthorized']);
  exit;
}
require __DIR__.'/../config/db.php';
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'user';
function json_ok($data = [], $extra = []){
  echo json_encode(array_merge(['ok'=>true,'data'=>$data], $extra)); exit;
}
function json_err($msg = 'error', $code = 400){
  http_response_code($code);
  echo json_encode(['ok'=>false, 'msg'=>$msg]); exit;
}
?>
