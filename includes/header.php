<?php

// 包含数据库连接和认证
require_once 'db.php';
require_once 'auth.php';

// 检查登录状态
if (!is_logged_in() && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: login.php');
    exit;
}

// 获取当前页面名称
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>星河小屋管理后台</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
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

        [data-theme="dark"] {
            --bg: #1a1a2e;
            --card-bg: #16213e;
            --text: #e0e0e0;
            --primary: #96c9ff;
            --secondary: #ffaaff;
            --accent: #ffd166;
            --shadow: rgba(255, 255, 255, 0.05);
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            transition: all 0.3s ease;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* 侧边栏样式 */
        .sidebar {
            width: 250px;
            background: var(--card-bg);
            padding: 1.5rem 0;
            border-right: 1px solid var(--glass-border);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .logo-admin {
            font-family: 'Kalam', cursive;
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            color: transparent;
            text-align: center;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }

        .admin-nav {
            padding: 0 1rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            margin: 0.5rem 0;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            color: var(--text);
            text-decoration: none;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--glass);
            transform: translateX(5px);
        }

        .nav-item i {
            margin-right: 0.8rem;
            width: 24px;
            text-align: center;
        }

        /* 主内容区 */
        .admin-main {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--glass-border);
        }

        .admin-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }

        .admin-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text);
            font-size: 1.2rem;
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background: var(--glass);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #888;
            margin-right: 15px;
        }

        .admin-profile img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        .admin-btn {
            padding: 0.6rem 1.2rem;
            background: var(--secondary);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .admin-btn.logout {
            background: #ff6b6b;
        }

        /* 内容卡片样式 */
        .content-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px var(--shadow);
        }

        .card-title {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .add-btn {
            background: var(--accent);
            color: #333;
            border: none;
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* 响应式设计 */
        @media (max-width: 991px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .logo-admin span,
            .nav-item span {
                display: none;
            }
            
            .admin-main {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }
            
            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }
            
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .admin-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="logo-admin">星河小屋</div>
            <nav class="admin-nav">
                <a href="index.php" class="nav-item <?= $current_page === 'index.php' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>控制面板</span>
                </a>
                <a href="skills.php" class="nav-item <?= $current_page === 'skills.php' ? 'active' : '' ?>">
                    <i class="fas fa-code"></i>
                    <span>技能管理</span>
                </a>
                <a href="projects.php" class="nav-item <?= $current_page === 'projects.php' ? 'active' : '' ?>">
                    <i class="fas fa-project-diagram"></i>
                    <span>项目管理</span>
                </a>
                <a href="friends.php" class="nav-item <?= $current_page === 'friends.php' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>友链管理</span>
                </a>
                 <a href="setting.php" class="nav-item <?= $current_page === 'setting.php' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span>网站设置</span>
                </a>
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>退出登录</span>
                </a>
            </nav>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">
                    <?php
                    $page_titles = [
                        'index.php' => '控制面板',
                        'skills.php' => '技能管理',
                        'projects.php' => '项目管理',
                        'friends.php' => '友链管理',
                        'login.php' => '系统登录'
                    ];
                    echo $page_titles[$current_page] ?? '星河小屋管理后台';
                    ?>
                </h1>
                <div class="admin-actions">
                    <?php if(is_logged_in()): ?>
                        <button class="theme-toggle" id="themeToggle">
                            <i class="fas fa-moon"></i>
                        </button>
                        <div class="admin-profile">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=8ec5fc&color=fff" alt="管理员">
                            <span>管理员</span>
                        </div>
                        <a href="logout.php" class="admin-btn logout">
                            <i class="fas fa-sign-out-alt"></i>
                            退出登录
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="admin-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            登录系统
                        </a>
                    <?php endif; ?>
                </div>
            </div>