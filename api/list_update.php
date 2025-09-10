<?php require __DIR__.'/_bootstrap.php';
$id = (int)($_POST['id'] ?? 0);
$sub_board_id = (int)($_POST['sub_board_id'] ?? 0);
$just_check = (int)($_POST['just_check'] ?? 0);

if(!$id || !$sub_board_id) json_err('id & sub_board_id required');
// Get target column name
$stmt = $pdo->prepare("SELECT sb.name, l.review_status FROM sub_boards sb JOIN lists l ON l.sub_board_id = l.sub_board_id WHERE sb.id=? LIMIT 1");
$stmt->execute([$sub_board_id]);
$sb_name = null;
$review = null;
// Fallback: get sub board name and current review
$stmt2 = $pdo->prepare("SELECT name FROM sub_boards WHERE id=?");
$stmt2->execute([$sub_board_id]);
$row = $stmt2->fetch();
$sb_name = $row ? $row['name'] : null;
$stmt3 = $pdo->prepare("SELECT review_status FROM lists WHERE id=?");
$stmt3->execute([$id]);
$r2 = $stmt3->fetch();
$review = $r2 ? $r2['review_status'] : 'none';

if ($sb_name === 'done' && $review !== 'approved'){
  json_err('Card belum approved review');
}

if ($just_check){
  json_ok();
}

$stmt = $pdo->prepare("UPDATE lists SET sub_board_id=? WHERE id=?");
$stmt->execute([$sub_board_id, $id]);
json_ok();
