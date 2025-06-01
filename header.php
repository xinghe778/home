<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <title>^小屋</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;600;800&family=Kalam:wght@300;700&display=swap" rel="stylesheet">
    <!-- Icons -->
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
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
            height: 100%;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background 0.5s ease;
            position: relative;
        }
         html {
          transition: background 0.5s ease, color 0.5s ease;
        }
        /* ====== 动态背景 ====== */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
            opacity: 0.2;
            animation: floatUp linear infinite;
        }

        @keyframes floatUp {
            0% { transform: translateY(100%); opacity: 0; }
            50% { opacity: 0.3; }
            100% { transform: translateY(-100vh); opacity: 0; }
        }

        /* ====== 卡片基础样式 ====== */
        .card-base {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .card-base:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-base::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, var(--primary), var(--secondary), var(--accent));
            opacity: 0.1;
            transition: transform 0.5s ease;
            z-index: -1;
        }

        .card-base:hover::before {
            transform: rotate(45deg);
        }

        /* ====== 主体样式 ====== */
        header {
            padding: 2rem 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            text-align: center;
            border-bottom-left-radius: 2rem;
            border-bottom-right-radius: 2rem;
            box-shadow: 0 4px 20px var(--shadow);
            position: relative;
            z-index: 1;
        }

        .logo {
            font-family: 'Kalam', cursive;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 1rem;
            transition: transform 0.3s ease, font-size 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05) rotate(2deg);
            font-size: 2.6rem;
        }

        nav a {
            color: var(--text);
            text-decoration: none;
            margin: 0 1rem;
            font-weight: 600;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        nav a:hover {
            transform: scale(1.1);
            color: var(--secondary);
        }

        .theme-toggle {
            position: absolute;
            right: 2rem;
            top: 1.5rem;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            transform: scale(1.05);
        }

        .theme-toggle i {
            margin-right: 0.5rem;
            transition: transform 0.3s ease;
        }

        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem 1rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        /* 玻璃拟态头像 */
        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 1.5rem;
            border: 4px solid var(--glass-border);
            backdrop-filter: blur(10px);
            background: var(--glass);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .avatar::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
            opacity: 0.2;
            animation: pulse 3s infinite ease-in-out;
            z-index: -1;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.2; }
            50% { transform: scale(1.1); opacity: 0.3; }
            100% { transform: scale(1); opacity: 0.2; }
        }

        .typewriter {
            font-size: 1.5rem;
            color: var(--primary);
            margin: 1rem 0;
        }

        /* 水波纹按钮 */
        .cta-button {
            padding: 0.8rem 2rem;
            background: var(--secondary);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .cta-button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, var(--secondary), transparent);
            transform: translate(-50%, -50%) scale(0.3);
            opacity: 0.2;
            transition: transform 0.5s ease;
        }

        .cta-button:hover::after {
            transform: translate(-50%, -50%) scale(1);
        }

        /* 光晕扩散 */
        .cta-button::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border: 2px dashed var(--primary);
            border-radius: 50px;
            opacity: 0.5;
            transition: all 0.5s ease;
        }

        .cta-button:hover::before {
            opacity: 1;
            transform: scale(1.05) rotate(3deg);
        }

        .section {
            max-width: 1000px;
            margin: 0 auto;
            padding: 3rem 1rem;
            position: relative;
            z-index: 1;
        }

        .section h2 {
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
        }

        .section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .about-content {
            display: flex;
            flex-direction: column-reverse;
            gap: 2rem;
            align-items: center;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        /* 技能卡片 */
        .skill-card {
            @extend .card-base;
        }

        .skill-icon {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .progress-bar {
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 10px;
            animation: grow 1.5s ease-in-out forwards;
        }

        @keyframes grow {
            from { width: 0; }
            to { width: var(--progress); }
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        /* 项目卡片 */
        .project-card {
            @extend .card-base;
        }

        .project-image {
            height: 200px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .project-content {
            padding: 1.5rem;
        }

        .project-like {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 50%;
            padding: 0.5rem;
            cursor: pointer;
            box-shadow: 0 2px 10px var(--shadow);
            transition: transform 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .project-like:hover {
            transform: scale(1.1) rotate(12deg);
        }

        .project-like i {
            color: #ff6b6b;
            transition: transform 0.3s ease;
        }

        .project-like.active i {
            transform: scale(1.2) rotate(360deg);
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 20px;
            border: none;
            background: var(--glass);
            resize: vertical;
            min-height: 100px;
            transition: background 0.3s ease, transform 0.3s ease;
            border: 1px solid var(--glass-border);
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            background: #f0f0f0;
            outline: none;
            transform: scale(1.01);
        }

        footer {
            text-align: center;
            padding: 2rem 1rem;
            font-size: 0.9rem;
            color: #666;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* 动画 */
        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        /* 响应式 */
        @media (min-width: 768px) {
            .about-content {
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-start;
            }

            .about-text {
                max-width: 60%;
            }

            .about-image {
                max-width: 35%;
            }
        }

        /* 社交卡片 */
        .social-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            z-index: 1;
        }

        .social-card {
            @extend .card-base;
            text-align: center;
            text-decoration: none;
            color: var(--text);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .social-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .social-card span {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .social-card i.github { color: var(--primary); }
        .social-card i.linkedin { color: #0077B5; }
        .social-card i.twitter { color: #1DA1F2; }
        .social-card i.instagram {
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            -webkit-background-clip: text;
            color: transparent;
        }
        .social-card i.email { color: var(--secondary); }
        .social-card i.website { color: var(--accent); }

        .social-card:hover i {
            transform: scale(1.1) rotate(5deg);
        }

        /* 友链卡片 */
        .friends-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            z-index: 1;
        }

        .friend-card {
            @extend .card-base;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text);
        }

        .friend-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 1rem;
            border: 2px solid var(--primary);
            transition: transform 0.3s ease;
        }

        .friend-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            transition: transform 0.5s ease;
        }

        .friend-card:hover .friend-avatar img {
            transform: scale(1.1) rotate(5deg);
        }

        .friend-info h4 {
            margin-bottom: 0.5rem;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .friend-info p {
            font-size: 0.95rem;
            line-height: 1.4;
            opacity: 0.8;
        }

        /* 移动端适配 */
        @media (max-width: 600px) {
            .friend-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .friend-avatar {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }
        .sunrise, .sunset {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: radial-gradient(circle, var(--primary), var(--secondary));
    animation: floatUp 3s infinite;
}

@keyframes floatUp {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
/* 底部整体样式 */
.site-footer {
    background: var(--glass);
    border-top: 1px solid var(--glass-border);
    backdrop-filter: blur(10px);
    padding: 2rem 1rem;
    font-size: 0.9rem;
    color: #888;
    position: relative;
    z-index: 1;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    align-items: center;
    text-align: center;
}

/* 左侧版权信息 */
.copyright {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
    font-weight: 500;
}

.copyright-symbol {
    font-size: 1.4rem;
    color: var(--primary);
    transition: transform 0.3s ease;
}

.copyright-symbol:hover {
    transform: rotate(12deg);
}

.year-range {
    opacity: 0.8;
}

.author {
    background: linear-gradient(45deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    color: transparent;
    font-weight: 600;
    transition: transform 0.3s ease;
}

.author:hover {
    transform: scale(1.05);
}

/* 中间备案信息 */
.license-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
}

.beian-link,
.gongan-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: #aaa;
    transition: all 0.3s ease;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
}

.beian-link:hover,
.gongan-link:hover {
    background: rgba(255, 255, 255, 0.05);
    color: var(--primary);
}

.beian-text {
    font-weight: 500;
}

.gongan-icon {
    width: 18px;
    height: 18px;
    transition: transform 0.3s ease;
}

.gongan-link:hover .gongan-icon {
    transform: scale(1.1);
}

/* 右侧装饰 */
.footer-emoji {
    font-size: 1.5rem;
    animation: float 3s infinite ease-in-out;
}

/* 响应式优化 */
@media (max-width: 768px) {
    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .license-info {
        flex-direction: row;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    
    .footer-left,
    .footer-right {
        display: none;
    }
}
    </style>
</head>
<body>
        <!-- 粒子背景 -->
    <canvas class="particles" id="particles-canvas"></canvas>

    <header>
        <div class="logo">✨ Xinghe Magic House ✨</div>
        <nav>
            <a href="#about">关于</a>
            <a href="#skills">技能</a>
            <a href="#projects">项目</a>
            <a href="#social">社交</a>
            <a href="#friends">伙伴</a>
        </nav>
        <div class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
            <span>切换主题</span>
        </div>
    </header>
