<?php require __DIR__.'/_bootstrap.php';
$id = (int)($_POST['id'] ?? 0);
if(!$id) json_err('id required');
$stmt = $pdo->prepare("DELETE FROM lists WHERE id=?");
$stmt->execute([$id]);
json_ok();
