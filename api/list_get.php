<?php require __DIR__.'/_bootstrap.php';
$id = (int)($_GET['id'] ?? 0);
if ($id){
  $stmt = $pdo->prepare("SELECT * FROM lists WHERE id=?");
  $stmt->execute([$id]);
  json_ok($stmt->fetchAll());
} else {
  $board_id = (int)($_GET['board_id'] ?? 0);
  if(!$board_id) json_err('board_id required');
  $stmt = $pdo->prepare("SELECT * FROM lists WHERE board_id=? ORDER BY id DESC");
  $stmt->execute([$board_id]);
  json_ok($stmt->fetchAll());
}
