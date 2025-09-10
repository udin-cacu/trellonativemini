<?php
session_start();
session_destroy();
header("Location: /trello_native_php/index.php");
