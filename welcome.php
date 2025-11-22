<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>

<h2>歡迎 <?php echo htmlspecialchars($_SESSION['user']['realname']); ?></h2>

<form method="post" action="logout.php">
    <button type="submit">登出</button>
</form>