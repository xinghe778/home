<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 检查是否已登录
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';
$default_username = 'admin';

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 验证输入
    if (empty($username) || empty($password)) {
        $error = '用户名和密码不能为空';
    } elseif (strlen($password) < 8) {
        $error = '密码长度至少为8个字符';
    } elseif ($password !== $confirm_password) {
        $error = '两次输入的密码不一致';
    } else {
        // 检查用户是否已存在
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user_exists = $stmt->fetchColumn();
            
            if ($user_exists) {
                $error = '该用户名已被使用';
            } else {
                // 创建新用户
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $hashed_password]);
                
                $success = '管理员账户创建成功！';
                
                // 自动登录
                login($username, $password);
                header('Location: index.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = '数据库错误: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创建管理员账户 - 星河小屋</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }
        
        :root {
            --primary: #ffb6c1;
            --secondary: #8ec5fc;
            --accent: #ffd166;
            --bg: #fffefc;
            --card-bg: #fff8f5;
            --text: #333;
            --shadow: rgba(0, 0, 0, 0.05);
            --glass: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.3);
            --error: #ff6b6b;
            --success: #7ed321;
        }
        
        body {
            background: linear-gradient(135deg, #ffb6c1, #8ec5fc);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        
        .account-setup-container {
            width: 100%;
            max-width: 500px;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            position: relative;
        }
        
        .setup-header {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            padding: 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .setup-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .setup-header p {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .setup-header::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .setup-header::after {
            content: "";
            position: absolute;
            bottom: -30px;
            left: -30px;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .setup-body {
            padding: 2.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(142, 197, 252, 0.3);
        }
        
        .password-strength {
            height: 5px;
            background: #eee;
            border-radius: 3px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        
        .password-strength-meter {
            height: 100%;
            width: 0%;
            background: var(--error);
            transition: width 0.3s ease;
        }
        
        .btn-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--secondary);
            color: white;
            flex: 1;
        }
        
        .btn-primary:hover {
            background: #6ba9e9;
            transform: translateY(-2px);
        }
        
        .security-tips {
            background: rgba(142, 197, 252, 0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1.5rem;
        }
        
        .security-tips h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: #444;
        }
        
        .tips-list {
            padding-left: 1.5rem;
        }
        
        .tips-list li {
            margin-bottom: 0.3rem;
        }
        
        .message {
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .message.error {
            background: rgba(255, 107, 107, 0.15);
            border: 1px solid var(--error);
            color: var(--error);
        }
        
        .message.success {
            background: rgba(126, 211, 33, 0.15);
            border: 1px solid var(--success);
            color: var(--success);
        }
        
        .message i {
            font-size: 1.2rem;
        }
        
        .required::after {
            content: " *";
            color: var(--error);
        }
        
        @media (max-width: 576px) {
            .setup-body {
                padding: 1.5rem;
            }
            
            .btn-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="account-setup-container">
        <div class="setup-header">
            <div class="header-content">
                <h1><i class="fas fa-user-shield"></i> 管理员账户初始化</h1>
                <p>欢迎使用星河小屋管理后台</p>
            </div>
        </div>
        
        <div class="setup-body">
            <?php if ($error): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= $success ?></span>
                </div>
            <?php endif; ?>
            
            <p>请创建您的第一个管理员账户：</p>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username" class="required">用户名</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?= htmlspecialchars($_POST['username'] ?? $default_username) ?>" 
                           required autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password" class="required">密码</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           required autocomplete="new-password">
                    <div class="password-strength">
                        <div class="password-strength-meter" id="password-meter"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="required">确认密码</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           required autocomplete="new-password">
                </div>
                
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> 创建管理员账户
                    </button>
                </div>
            </form>
            
            <div class="security-tips">
                <h3><i class="fas fa-shield-alt"></i> 账户安全提示</h3>
                <ul class="tips-list">
                    <li>使用至少8个字符的密码，包含字母、数字和特殊符号</li>
                    <li>不要使用常见密码如 "123456" 或 "password"</li>
                    <li>定期更换密码以提高安全性</li>
                    <li>不要共享您的管理员账户信息</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // 密码强度检测
        const passwordInput = document.getElementById('password');
        const passwordMeter = document.getElementById('password-meter');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // 长度检查
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 15;
            
            // 字符类型检查
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            
            // 更新强度条
            passwordMeter.style.width = strength + '%';
            
            // 更新颜色
            if (strength < 50) {
                passwordMeter.style.backgroundColor = '#ff6b6b';
            } else if (strength < 75) {
                passwordMeter.style.backgroundColor = '#ffd166';
            } else {
                passwordMeter.style.backgroundColor = '#7ed321';
            }
        });
        
        // 表单提交前的密码匹配检查
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('两次输入的密码不一致，请重新输入');
            }
        });
    </script>
<?php
// 包含公共底部
require_once '../includes/footer.php';
?>