<?php require __DIR__.'/_bootstrap.php';
$stmt = $pdo->prepare("
  SELECT b.*, (SELECT COUNT(*) FROM lists l WHERE l.board_id = b.id) AS total_lists
  FROM boards b WHERE b.user_id=? ORDER BY b.created_at DESC
");
$stmt->execute([$user_id]);
json_ok($stmt->fetchAll());
