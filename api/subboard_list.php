<?php require __DIR__.'/_bootstrap.php';
$board_id = (int)($_GET['board_id'] ?? 0);
if (!$board_id) json_err('board_id required');
$stmt = $pdo->prepare("SELECT * FROM sub_boards WHERE board_id=? ORDER BY position ASC");
$stmt->execute([$board_id]);
json_ok($stmt->fetchAll());
