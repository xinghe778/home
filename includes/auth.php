<?php
session_start();

// 检查用户是否登录
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// 登录验证
function login($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

// 登出
function logout() {
    session_unset();
    session_destroy();
}
?>