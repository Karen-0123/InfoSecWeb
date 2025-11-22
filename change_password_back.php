<?php
    session_start();
    $errors = [];
    require_once('conn.php');

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
        header("Location: change_password.php");
    }

    // 檢查密碼複雜度
    
    if (strlen($newPassword) < 8) $errors[] = "密碼至少8碼";
    if (!preg_match('/[A-Z]/', $newPassword)) $errors[] = "需包含大寫字母";
    if (!preg_match('/[a-z]/', $newPassword)) $errors[] = "需包含小寫字母";
    if (!preg_match('/[0-9]/', $newPassword)) $errors[] = "需包含數字";

    // 檢查是否與前3代密碼相同
    $stmt = $conn->prepare("SELECT `password`, `password1`, `password2` FROM users WHERE account=?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $newHashed = hash('sha256', $newPassword);
    if (
        $newHashed === $user['password'] || 
        $newHashed === $user['password1'] || // 預設為: Kelly01234 587217c6d850eb6a47cb7fc4b8f37096cba5a91b172813025b6ef0e0cfae9b04
        $newHashed === $user['password2']    // 預設為: Kelly012345 7050036f21b2510a0b35b1643ce4e0a7137bb4a56c91c67f6c78632558a6a12d
    ) {
        $errors[] = '新密碼不得與前3代密碼相同';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: change_password.php");
        exit();
    }

    // 更新密碼並輪替歷史密碼
    $stmt = $conn->prepare("
        UPDATE users SET 
            password2 = password1,
            password1 = password,
            password = ?,
            init = 0 
        WHERE account = ?
    ");

    $stmt->bind_param("ss", $newHashed, $account);
    if ($stmt->execute()) {
        echo "密碼更新成功！";
        header("Location: welcome.php");
    } else {
        echo "更新失敗：" . $stmt->error;
    }
?>