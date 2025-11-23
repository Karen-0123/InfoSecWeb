<form method="POST" action="test.php" id="registerForm">
    <h2>Web 應用系統</h2>

    姓名：<input type="text" name="realname" required>
    <br><br>

    帳號：<input type="text" name="account" id="account" required>
    <span id="msg0" style="color:red;"></span>
    <br><br>
    
    密碼：<input type="password" name="password" id="password" required>
    <span id="msg1" style="color:red;"></span>
    <br><br>

    <button type="submit">登入</button>

    <?php
    session_start();

    if (isset($_SESSION['errors']['login'])) {
        foreach ($_SESSION['errors']['login'] as $err) {
            echo '<p style="color:red;">' . htmlspecialchars($err) . '</p>';
        }
        unset($_SESSION['errors']['login']); // 顯示完清除
    }
    ?>
</form>

<script>

let validAccount = true;
let validPassword = true;

document.getElementById("account").addEventListener("input", function() {
    const value = this.value;
    const msg = document.getElementById("msg0");

    if (/^[A-Za-z0-9]*$/.test(value)) {
        msg.textContent = ""; 
        validAccount = true;
    } else {
        msg.textContent = "帳號只能輸入英數字！";
        validAccount = false;
    }
});

document.getElementById("password").addEventListener("input", function() {
    const value = this.value;
    const msg = document.getElementById("msg1");

    if (value.includes("'") || value.includes('"')) {
        msg.textContent = "密碼不得包含單引號或雙引號！";
        validPassword = false;
    } else {
        msg.textContent = "";
        validPassword = true;
    }
});

document.getElementById("registerForm").addEventListener("submit", function(e) {
    if (!validAccount || !validPassword) {
        e.preventDefault();
        alert("輸入格式有誤，請修正後再送出！");
    }
});
</script>

