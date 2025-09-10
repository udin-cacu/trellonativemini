<?php require __DIR__.'/_bootstrap.php';
$board_id = (int)($_GET['board_id'] ?? 0);
if(!$board_id) json_err('board_id required');
$stmt = $pdo->prepare("SELECT id, title, deadline FROM lists WHERE board_id=? AND deadline IS NOT NULL AND deadline < NOW() ORDER BY deadline ASC LIMIT 10");
$stmt->execute([$board_id]);
json_ok($stmt->fetchAll());
