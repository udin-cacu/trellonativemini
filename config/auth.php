<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['user_id'])) {
  header("Location: /trello_native_php/index.php");
  exit;
}
?>
