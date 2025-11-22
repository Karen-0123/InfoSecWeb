<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>

<form method="POST" action="change_password_back.php" id="changePasswordForm">
    <h2>初次登入，請變更密碼</h2>

    輸入新密碼：<input type="password" name="newPassword" id="newPassword" required>
    <span id="msg0" style="color:red;"></span>
    <br><br>

    再次輸入新密碼：<input type="password" name="againPassword" id="againPassword" required>
    <span id="msg1" style="color:red;"></span>
    <br><br>

    <button type="submit">更改</button>

    <?php
    session_start();

    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $err) {
            echo '<p style="color:red;">' . htmlspecialchars($err) . '</p>';
        }
        unset($_SESSION['errors']); // 顯示完清除
    }
    ?>
</form>