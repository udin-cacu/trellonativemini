<?php require __DIR__.'/_bootstrap.php';
$id = (int)($_POST['id'] ?? 0);
$board_id = (int)($_POST['board_id'] ?? 0);
$sub_board_id = (int)($_POST['sub_board_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$desc = trim($_POST['description'] ?? '');
$assignee = trim($_POST['assignee'] ?? '');
$priority = $_POST['priority'] ?? 'low';
$deadline = $_POST['deadline'] ?? null;
$review_status = $_POST['review_status'] ?? 'none';
$review_notes = $_POST['review_notes'] ?? null;
$labels = $_POST['labels'] ?? null;

if (!$title) json_err('title required');
if ($deadline === '') $deadline = null;

if ($id){
  $stmt = $pdo->prepare("UPDATE lists SET sub_board_id=?, title=?, description=?, assignee=?, priority=?, deadline=?, review_status=?, review_notes=?, labels=? WHERE id=?");
  $stmt->execute([$sub_board_id, $title, $desc, $assignee, $priority, $deadline, $review_status, $review_notes, $labels, $id]);
  json_ok(['id'=>$id]);
} else {
  if(!$board_id || !$sub_board_id) json_err('board_id & sub_board_id required');
  $stmt = $pdo->prepare("INSERT INTO lists (board_id, sub_board_id, title, description, assignee, priority, deadline, review_status, review_notes, labels) VALUES (?,?,?,?,?,?,?,?,?,?)");
  $stmt->execute([$board_id, $sub_board_id, $title, $desc, $assignee, $priority, $deadline, $review_status, $review_notes, $labels]);
  json_ok(['id'=>$pdo->lastInsertId()]);
}
