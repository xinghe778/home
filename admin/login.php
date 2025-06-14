<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// 启动会话
session_start();

// 初始化错误消息
$error = '';

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "用户名和密码不能为空";
    } else {
        if (login($username, $password)) {
            // 登录成功后重定向到首页
            header('Location: index.php');
            exit;
        } else {
            $error = "用户名或密码错误";
        }
    }
}

// 如果用户已登录，重定向到首页
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>星河小屋 - 管理登录</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&family=Kalam:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }
        
        :root {
            --primary: #ffb6c1;
            --primary-dark: #e5a4ae;
            --secondary: #8ec5fc;
            --secondary-dark: #7fb3e8;
            --accent: #ffd166;
            --text: #333;
            --light: #fffefc;
            --light-bg: #fff8f5;
            --glass: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.3);
            --shadow: 0 15px 30px rgba(0,0,0,0.15);
            --error: #ff6b6b;
            --success: #4caf50;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ffb6c1, #8ec5fc);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            overflow-x: hidden;
            position: relative;
            background-attachment: fixed;
        }
        
        /* 星空背景装饰 */
        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }
        
        .star {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: twinkle var(--duration, 3s) infinite ease-in-out;
        }
        
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            z-index: 1;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-intro {
            flex: 1;
            padding: 2rem;
            color: white;
            text-align: center;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation-delay: 0.2s;
        }
        
        .intro-content {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            max-width: 100%;
            box-shadow: var(--shadow);
            animation: slideInLeft 0.6s ease-out;
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .logo {
            font-family: 'Kalam', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #ffd166, #fff);
            -webkit-background-clip: text;
            color: transparent;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .intro-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .intro-text {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
            max-width: 400px;
        }
        
        .features {
            text-align: left;
            margin-top: 1.5rem;
        }
        
        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .feature:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .feature i {
            background: rgba(255, 255, 255, 0.2);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .login-box {
            background: var(--light-bg);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            z-index: 2;
            animation: slideInRight 0.6s ease-out;
        }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .login-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }
        
        .login-box::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .login-title {
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: 2rem;
            background: linear-gradient(45deg, #ffb6c1, #8ec5fc);
            -webkit-background-clip: text;
            color: transparent;
            font-weight: 700;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .login-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }
        
        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }
        
        .form-group label {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 2;
        }
        
        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            position: relative;
            z-index: 1;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #8ec5fc;
            box-shadow: 0 0 0 4px rgba(142, 197, 252, 0.2);
        }
        
        .form-control:focus + label,
        .form-control:not(:placeholder-shown) + label {
            top: -10px;
            left: 0.8rem;
            font-size: 0.85rem;
            background: var(--light-bg);
            padding: 0 0.5rem;
            color: #8ec5fc;
            z-index: 3;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #bbb;
            font-size: 1.2rem;
            z-index: 2;
        }
        
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(142, 197, 252, 0.4);
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(142, 197, 252, 0.6);
            background: linear-gradient(90deg, var(--primary-dark), var(--secondary-dark));
        }
        
        .btn:active {
            transform: translateY(1px);
        }
        
        .btn::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-100%);
            transition: transform 0.4s ease;
        }
        
        .btn:hover::after {
            transform: translateX(100%);
        }
        
        .error {
            color: var(--error);
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0.8rem;
            border-radius: 8px;
            background: rgba(255, 107, 107, 0.1);
            border: 1px solid rgba(255, 107, 107, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .error i {
            font-size: 1.2rem;
        }
        
        .forgot-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #777;
            font-size: 0.9rem;
        }
        
        .forgot-link a {
            color: #8ec5fc;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .forgot-link a:hover {
            color: #ffb6c1;
        }
        
        .forgot-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: #ffb6c1;
            transition: width 0.3s ease;
        }
        
        .forgot-link a:hover::after {
            width: 100%;
        }
        
        .copyright {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
        }
        
        /* 响应式设计 */
        @media (max-width: 991px) {
            .login-intro {
                display: none;
            }
            
            .login-box {
                max-width: 500px;
            }
        }
        
        @media (max-width: 576px) {
            .login-box {
                padding: 1.8rem;
            }
            
            .login-title {
                font-size: 1.8rem;
            }
            
            .form-control {
                padding: 0.9rem 0.9rem 0.9rem 2.8rem;
            }
            
            .input-icon {
                left: 0.9rem;
                font-size: 1.1rem;
            }
        }
        
        /* 加载动画 */
        .loading-spinner {
            display: inline-block;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- 星空背景 -->
    <div class="stars" id="stars"></div>
    
    <div class="login-container">
        <div class="login-intro">
            <div class="intro-content">
                <div class="logo">星河小屋</div>
                <h2 class="intro-title">管理控制台</h2>
                <p class="intro-text">欢迎回到星河小屋管理后台，请使用您的管理员账户登录系统</p>
                
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <span>简洁高效的界面设计</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-bolt"></i>
                        <span>直观的操作体验</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-mobile-alt"></i>
                        <span>全平台响应式设计</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-palette"></i>
                        <span>优雅美观的用户界面</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="login-box">
            <h1 class="login-title">管理员登录</h1>
            
            <?php if (!empty($error)): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="loginForm">
                <div class="form-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control" placeholder=" " id="username" name="username" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                           required autocomplete="username">
                    <label for="username">用户名</label>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" placeholder=" " id="password" name="password" 
                           required autocomplete="current-password">
                    <label for="password">密码</label>
                </div>
                
                <button type="submit" class="btn" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> 登录系统
                </button>
            </form>
        </div>
    </div>
    
    <div class="copyright">
       CopyRight &copy; <?php echo date('Y'); ?> 星河小屋 - 版权所有
    </div>
    
    <script>
        // 创建星空背景
        function createStars() {
            const starsContainer = document.getElementById('stars');
            const starCount = 150;
            
            for (let i = 0; i < starCount; i++) {
                const star = document.createElement('div');
                star.classList.add('star');
                
                // 随机大小（1-3px）
                const size = Math.random() * 2 + 1;
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                
                // 随机位置
                star.style.left = `${Math.random() * 100}%`;
                star.style.top = `${Math.random() * 100}%`;
                
                // 随机闪烁动画时长
                star.style.setProperty('--duration', `${Math.random() * 4 + 2}s`);
                
                starsContainer.appendChild(star);
            }
        }
        
        // 表单提交效果
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = this.querySelector('#loginBtn');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            
            // 简单的客户端验证
            if (!usernameInput.value.trim()) {
                e.preventDefault();
                showError("请输入用户名");
                usernameInput.focus();
                return;
            }
            
            if (!passwordInput.value) {
                e.preventDefault();
                showError("请输入密码");
                passwordInput.focus();
                return;
            }
            
            // 显示加载状态
            button.innerHTML = '<i class="fas fa-spinner loading-spinner"></i> 登录中...';
            button.disabled = true;
        });
        
        // 显示错误消息
        function showError(message) {
            let errorElement = document.querySelector('.error');
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'error';
                const loginBox = document.querySelector('.login-box');
                loginBox.insertBefore(errorElement, document.querySelector('form'));
            }
            
            errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
            
            // 添加抖动效果
            errorElement.style.animation = 'none';
            setTimeout(() => {
                errorElement.style.animation = 'shake 0.5s ease';
            }, 10);
        }
        
        // 初始化
        window.addEventListener('DOMContentLoaded', () => {
            createStars();
            
            // 如果有错误消息，添加抖动效果
            if (document.querySelector('.error')) {
                document.querySelector('.error').style.animation = 'shake 0.5s ease';
            }
        });
    </script>
</body>
</html>