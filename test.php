<?php
    session_start();
    $errors = [];
    
    $account = $_POST['account'];
    $password = $_POST['password'];
    $realname = $_POST['realname'];

    $ip = $_SERVER['REMOTE_ADDR'];

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
        $errors[] = "資料庫查詢失敗: " . $stmt->error;
        header("Location: index.php");
    }

    $result = $stmt->get_result();

    // 不會同時帳號不存在&密碼錯誤
    if ($result->num_rows === 0) {
        $errors[] = "帳號不存在";

    }else{

        // 帳號存在
        $user = $result->fetch_assoc();

        $sql = "SELECT * 
            FROM login_log 
            WHERE account = ?
              AND TIMESTAMPDIFF(MINUTE, login_time, NOW()) < 15
              AND locked = 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $account);
        $stmt->execute();
        $lockedResult = $stmt->get_result();

        if ($lockedResult->num_rows > 0) {
            $errors[] = "帳號已被鎖定，請 15 分鐘後再試。";

            // 記錄這次的登入 log（因為嘗試但被鎖）
            $log = $conn->prepare(
                "INSERT INTO login_log (account, login_time, ip_address, result, locked)
                VALUES (?, NOW(), ?, 0, 1)"
            );
            $log->bind_param("ss", $account, $ip);
            $log->execute();

        } else {

        // 檢查密碼是否正確
        $hashed = hash('sha256', $password);

        if ($hashed !== $user['password']) {

            // 密碼錯誤 → 檢查最近 15 分鐘內失敗次數
            $sql = "SELECT COUNT(*)
                    FROM login_log
                    WHERE account = ?
                      AND TIMESTAMPDIFF(MINUTE, login_time, NOW()) < 15
                      AND result = 0";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $account);
            $stmt->execute();
            $countResult = $stmt->get_result();
            $row = $countResult->fetch_row();
            $failCount = $row[0];  // 最近15分鐘的失敗次數???

            // 第5次失敗 → 鎖定
            $lock = ($failCount >= 4) ? 1 : 0;

            // 記錄此次失敗
            $log = $conn->prepare(
                "INSERT INTO login_log (account, login_time, ip_address, result, locked)
                 VALUES (?, NOW(), ?, 0, ?)"
            );
            $log->bind_param("ssi", $account, $ip, $lock);
            $log->execute();

            $errors[] = "密碼錯誤";

        } else {
            // 密碼正確 → 記錄成功登入
            $log = $conn->prepare(
                "INSERT INTO login_log (account, login_time, ip_address, result, locked)
                 VALUES (?, NOW(), ?, 1, 0)"
            );
            $log->bind_param("ss", $account, $ip);
            $log->execute();

            // 建立 Session
            $_SESSION['user'] = [
                'account' => $user['account'],
                'realname' => $user['realname']
            ];

            // 檢查是否為預設密碼
            if ($user['init']) {
                header("Location: change_password.php");
                exit();
            }

            header("Location: welcome.php");
            exit();
        }
    }
    // 收集完錯誤才進行跳轉
    if (!empty($errors)) {
        $_SESSION['errors']['login'] = $errors;
        header("Location: index.php");
        exit();
    }
}
    // 收集完錯誤才進行跳轉
    if (!empty($errors)) {
        $_SESSION['errors']['login'] = $errors;
        header("Location: index.php");
        exit();
    }
?>