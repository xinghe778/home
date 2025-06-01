<?php
include('header.php');
?>
    <section class="hero">
        <div class="avatar animate-float">
            <img src="http://q.qlogo.cn/headimg_dl?dst_uin=2931468138&spec=640&img_type=jpg" alt="qianshan" style="border-radius: 50%; width: 100px;">
        </div>
        <h1>你好呀！我是Xinghe</h1>
        <div class="typewriter" id="typewriter"></div>
        <button class="cta-button">下载我的简历</button>
    </section>

    <section class="section" id="about">
        <h2>关于我 😊</h2>
        <div class="about-content">
            <div class="about-text">
                <p>一个热爱设计和编程的前端开发者，擅长使用python和vue创造有趣的小项目。</p>
                <p>喜欢把技术变得可爱，相信代码和彩虹都可以治愈生活。</p>
                <p>最好来点养生茶</p>
            </div>
            <div class="about-image">
                <img src="chutian.png" alt="插画" style="width: 320px;">
            </div>
        </div>
    </section>

    <section class="section" id="skills">
        <h2>技能树 🌱</h2>
        <div class="skills-grid">
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-code"></i></div>
                <h3>前端开发</h3>
                <p>Python / Vue / JavaScript / HTML / CSS / PHP</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="--progress: 85%"></div>
                </div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-paint-roller"></i></div>
                <h3>传统文化</h3>
                <p>正骨 / 脉诊 / 针灸 / 相面 / 养生 / 减肥 / 玄学</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="--progress: 75%"></div>
                </div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-cube"></i></div>
                <h3>体育运动</h3>
                <p>滑板 / 篮球 / 足球 / 羽毛球 / 骑行 </p>
                <div class="progress-bar">
                    <div class="progress-fill" style="--progress: 65%"></div>
                </div>
            </div>
            <div class="skill-card">
                <div class="skill-icon"><i class="fas fa-music"></i></div>
                <h3>其他爱好</h3>
                <p>机车 / 音乐制作 / 吉他 / 音乐节 / 美食</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="--progress: 90%"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="projects">
        <h2>趣味项目 🎨</h2>
        <div class="projects-grid">
            <div class="project-card">
                <div class="project-like" onclick="toggleLike(this)">
                    <i class="far fa-heart"></i>
                </div>
                <div class="project-image">
                    <img src="chutian.png" alt="AI助手">
                </div>
                <div class="project-content">
                    <h3>AI助手</h3>
                    <p>帮助用户快速回复访问者问题,解决消息滞后.</p>
                </div>
            </div>
            <div class="project-card">
                <div class="project-like" onclick="toggleLike(this)">
                    <i class="far fa-heart"></i>
                </div>
                <div class="project-image">
                    <img src="https://admin.spanstar.cn/dashboard.png" alt="展示">
                </div>
                <div class="project-content">
                    <h3>Qianshan财务系统</h3>
                    <p>打造轻量级记账系统</p>
                </div>
            </div>
            <div class="project-card">
                <div class="project-like" onclick="toggleLike(this)">
                    <i class="far fa-heart"></i>
                </div>
                <div class="project-image">
                    <img src="rixiangchutian.png" alt="音乐可视化">
                </div>
                <div class="project-content">
                    <h3>音乐可视化</h3>
                    <p>随节奏跳动的波形图谱，让音乐看得见</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="social">
        <h2>我的社交星球 🌐</h2>
        <div class="social-grid">
            <a href="https://github.com/yourname" target="_blank" class="social-card github">
                <i class="fab fa-github"></i>
                <span>GitHub</span>
            </a>
            <a href="Fuzhen88962" target="_blank" class="social-card tencent">
                <i class="fa-brands fa-weixin"></i></i>
                <span>WeChat</span>
            </a>
            <a href="https://twitter.com/yourname" target="_blank" class="social-card twitter">
                <i class="fab fa-twitter"></i>
                <span>Twitter</span>
            </a>
            <a href="https://instagram.com/yourname" target="_blank" class="social-card instagram">
                <i class="fab fa-instagram"></i>
                <span>Instagram</span>
            </a>
            <a href="mailto:fuzhen@88.cn" class="social-card email">
                <i class="fas fa-envelope"></i>
                <span>Email Me</span>
            </a>
            <a href="https://blog.spanstar.cn" target="_blank" class="social-card website">
                <i class="fas fa-globe"></i>
                <span>My Site</span>
            </a>
        </div>
    </section>

    <section class="section" id="friends">
        <h2>我的小伙伴 🧸</h2>
        <div class="friends-grid">
            <a href="https://xiaoqianlan.com/" target="_blank" class="friend-card">
                <div class="friend-avatar">
                    <img src="https://xiaoqianlan.com/favicon.ico" alt="友链头像" />
                </div>
                <div class="friend-info">
                    <h4>浅蓝的小窝</h4>
                    <p>一名沉迷轻小说漫画动漫二次元游戏的自宅警备员，热爱Tech和ACG文化，生活中的一只小透明。</p>
                </div>
            </a>
            <a href="#" target="_blank" class="friend-card">
                <div class="friend-avatar">
                    <img src="g.jpg" alt="友链头像" />
                </div>
                <div class="friend-info">
                    <h4>等待添加</h4>
                    <p>博客像云朵一样轻盈☁️</p>
                </div>
            </a>
            <a href="#" target="_blank" class="friend-card">
                <div class="friend-avatar">
                    <img src="g.jpg" alt="友链头像" />
                </div>
                <div class="friend-info">
                    <h4>等待添加</h4>
                    <p>代码森林探险家🌲</p>
                </div>
            </a>
        </div>
    </section>
<?php 
include('footer.php');
?>