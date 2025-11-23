<?php
    session_start();
    $errors = [];

    if (!isset($_SESSION['user'])) {
        header("Location: index.php");
        exit();
    }

    $account = $_SESSION['user']['account'];
    $newPassword = $_POST['newPassword'];
    $again = $_POST['againPassword'];

    // 檢查兩次輸入是否一致
    if ($newPassword !== $again) {
        $errors[] = "兩次輸入密碼不相同";
    }

    // 檢查密碼複雜度
    if (strlen($newPassword) < 8) $errors[] = "密碼至少8碼";
    if (!preg_match('/[A-Z]/', $newPassword)) $errors[] = "需包含大寫字母";
    if (!preg_match('/[a-z]/', $newPassword)) $errors[] = "需包含小寫字母";
    if (!preg_match('/[0-9]/', $newPassword)) $errors[] = "需包含數字";

    // 前面已收集到錯誤 不做DB查詢
    if (empty($errors)) {
        require_once('conn.php');

        $stmt = $conn->prepare("SELECT `password`, `password1`, `password2` FROM users WHERE account = ?");
        if ($stmt === false) {
            $errors[] = "資料庫錯誤(prepare)";
        } else {
            $stmt->bind_param("s", $account);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $errors[] = "帳號不存在";
            } else {
                $user = $result->fetch_assoc();
                // 可嘗試改成 password_hash 強化
                $newHashed = hash('sha256', $newPassword);

                if (
                    $newHashed === $user['password'] ||
                    $newHashed === $user['password1'] ||
                    $newHashed === $user['password2']
                ) {
                    $errors[] = "新密碼不得與前3代密碼相同";
                } else {
                    // 沒有錯誤 -> 更新 DB
                    $upd = $conn->prepare(
                        "UPDATE users
                        SET password2 = password1,
                            password1 = password,
                            password = ?
                        WHERE account = ?"
                    );
                    if ($upd === false) {
                        $errors[] = "資料庫錯誤(prepare update)";
                    } else {
                        $upd->bind_param("ss", $newHashed, $account);
                        if (!$upd->execute()) {
                            $errors[] = "更新密碼失敗，請稍後重試";
                        } else {
                            $_SESSION['success'] = "密碼變更成功";
                        }
                        $upd->close();
                    }
                }
            }
            $stmt->close();
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors']['changePassword'] = $errors;
        header("Location: change_password.php");
        exit();
    } else {
        header("Location: welcome.php");
        exit();
    }
?>