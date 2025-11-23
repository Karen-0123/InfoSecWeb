<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

session_unset();   // 清除所有 session 變數
session_destroy(); // 刪除 session

header("Location: index.php"); // 登出後跳回登入頁
exit();
?>
