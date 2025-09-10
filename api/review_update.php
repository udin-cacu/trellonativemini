<?php require __DIR__.'/_bootstrap.php';
$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'pending';
if(!$id) json_err('id required');
if(!in_array($status, ['approved','revisi','pending'])) json_err('invalid status');
$stmt = $pdo->prepare("UPDATE lists SET review_status=? WHERE id=?");
$stmt->execute([$status, $id]);
json_ok();
