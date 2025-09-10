<?php
require '../config/db.php';
session_start();

$title = $_POST['title'] ?? '';
$desc = $_POST['description'] ?? '';
$priority = $_POST['priority'] ?? 'medium';
$deadline = $_POST['deadline'] ?? null;
$sub_id = $_POST['sub_board_id'] ?? null;
$labels = isset($_POST['labels']) ? json_encode($_POST['labels']) : '[]';

if ($title && $sub_id) {
    $stmt = $pdo->prepare("INSERT INTO lists 
        (sub_board_id,title,description,priority,labels,deadline) 
        VALUES (?,?,?,?,?,?)");
    $stmt->execute([$sub_id,$title,$desc,$priority,$labels,$deadline]);
    echo json_encode(['status'=>'ok']);
} else {
    echo json_encode(['status'=>'error','msg'=>'Data tidak lengkap']);
}
