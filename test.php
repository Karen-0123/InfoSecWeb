<?php
    $account = $_POST['account'];
    $password = $_POST['password'];
    $realname = $_POST['realname'];

    // 後端再次驗證
    if (empty($account) || empty($password) || empty($realname)) {
        header('Location: index.php?error=表單不能為空');
        exit();
    }

    // 帳號
    if (!preg_match("/^[A-Za-z0-9]+$/", $account)) {
        header('Location: index.php?error=帳號只能輸入英數字');
        exit();
    }

    // 密碼
    if (strpos($password, "'") !== false || strpos($password, '"') !== false) {
        header('Location: index.php?error=密碼不能含引號');
        exit();
    }

    // 連資料庫檢查帳號是否存在
    require_once('conn.php');

    $sql = "SELECT * FROM users WHERE account= ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $account);

    if (!$stmt->execute()) {
        die("資料庫查詢失敗: " . $stmt->error);
    }

    $result = $stmt->get_result();

    // 帳號不存在
    if ($result->num_rows === 0) {
        die("帳號不存在");
    }
    
    // 帳號存在
    $user = $result->fetch_assoc();

    // 檢查密碼是否正確
    $hashed = hash('sha256', $password);
    if ($hashed !== $user['password']) {
        die("密碼錯誤");
    }

    // 登入成功，建立 Session
    session_start();
    $_SESSION['user'] = [
        'account' => $user['account'],
        'realname' => $user['realname']
    ];

    // 檢查是否為預設密碼
    if ($user['init']) {
        header("Location: change_password.php");
        exit();
    }

    // 導向歡迎頁
    header("Location: welcome.php");
    exit();
?>