<?php require __DIR__.'/_bootstrap.php';
$title = trim($_POST['title'] ?? '');
if (!$title) json_err('Judul wajib');
$pdo->beginTransaction();
try{
  $stmt = $pdo->prepare("INSERT INTO boards (user_id, title) VALUES (?,?)");
  $stmt->execute([$user_id, $title]);
  $board_id = $pdo->lastInsertId();
  // create default sub boards
  $names = ['todo','progres','review','done'];
  foreach ($names as $i => $n){
    $stmt2 = $pdo->prepare("INSERT INTO sub_boards (board_id, name, position) VALUES (?,?,?)");
    $stmt2->execute([$board_id, $n, $i]);
  }
  $pdo->commit();
  json_ok(['id'=>$board_id]);
}catch(Exception $e){
  $pdo->rollBack();
  json_err($e->getMessage());
}
