<?php
file_put_contents("../log.txt", date("Y-m-d H:i:s")." ADD_BOARD CALLED\n", FILE_APPEND);

require '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $user_id = $_SESSION['user_id'];

    try {
        // 1. Insert board
        $stmt = $pdo->prepare("INSERT INTO boards (user_id, title, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $title]);

        // 2. Ambil id board yang baru dibuat
        $board_id = $pdo->lastInsertId();

        // 3. Insert default sub_board
        $defaults = [
            ['name' => 'Todo',     'position' => 1],
            ['name' => 'Progress', 'position' => 2],
            ['name' => 'Review',   'position' => 3],
            ['name' => 'Done',     'position' => 4],
        ];

        $stmtSub = $pdo->prepare("INSERT INTO sub_boards (board_id, name, position, created_at) VALUES (?, ?, ?, NOW())");

        foreach ($defaults as $sub) {
            $stmtSub->execute([$board_id, $sub['name'], $sub['position']]);
        }

        echo "success";
    } catch (Exception $e) {
        echo "error: " . $e->getMessage();
    }
}
