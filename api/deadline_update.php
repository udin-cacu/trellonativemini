<?php require __DIR__.'/_bootstrap.php';
$id = (int)($_POST['id'] ?? 0);
$add_minutes = (int)($_POST['add_minutes'] ?? 30);
if(!$id) json_err('id required');
$stmt = $pdo->prepare("UPDATE lists SET deadline = IF(deadline IS NULL, DATE_ADD(NOW(), INTERVAL ? MINUTE), DATE_ADD(deadline, INTERVAL ? MINUTE)) WHERE id=?");
$stmt->execute([$add_minutes, $add_minutes, $id]);
json_ok();
