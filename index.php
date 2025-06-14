<?php
include('header.php');
?>
    <section class="hero">
        <div class="avatar animate-float">
            <img src="http://q.qlogo.cn/headimg_dl?dst_uin=2931468138&spec=640&img_type=jpg" alt="qianshan" style="border-radius: 50%; width: 100px;">
        </div>
        <h1>你好呀！我是<?=$setting['site_author']?></h1>
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
                <img src="assets/chutian.png" alt="插画" style="width: 320px;">
            </div>
        </div>
    </section>

    <section class="section" id="skills">
        <h2>技能树 🌱</h2>
        <div class="skills-grid">
            <?php while($skill = $stmt_skills->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="skill-card">
                <div class="skill-icon"><i class="<?=htmlspecialchars($skill['icon'])?>"></i></div>
                <h3><?=htmlspecialchars($skill['title'])?></h3>
                <p><?=htmlspecialchars($skill['description'])?></p>
                <div class="progress-bar">
                    <div class="progress-fill" style="--progress: <?=$skill['percentage']?>%"></div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="section" id="projects">
        <h2>趣味项目 🎨</h2>
         <div class="projects-grid">
            <?php while($project = $stmt_projects->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="project-card">
                <div class="project-like" onclick="toggleLike(this)">
                    <i class="far fa-heart"></i>
                </div>
                <div class="project-image">
                    <img src="<?=htmlspecialchars($project['image_url'])?>" alt="<?=htmlspecialchars($project['name'])?>">
                </div>
                <div class="project-content">
                    <h3><?=htmlspecialchars($project['name'])?></h3>
                    <p><?=htmlspecialchars($project['description'])?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

   <section class="section" id="social">
    <h2>我的社交星球 🌐</h2>
    <div class="social-grid">
        <?php if (!empty($socialLinks)): ?>
            <?php foreach ($socialLinks as $link): ?>
                <?php
                    $icon = htmlspecialchars($link['icon'] ?? '');
                    $url = htmlspecialchars($link['url'] ?? '#');
                    $name = htmlspecialchars($link['name'] ?? '');
                    // 特殊处理微信链接
                    if (stripos($name, 'wechat') !== false || stripos($name, '微信') !== false) {
                        $url = "javascript:alert('{$url}')";
                    }
                ?>
                <a href="<?= $url ?>" target="_blank" class="social-card <?= strtolower($name) ?>">
                    <i class="<?= $icon ?>"></i>
                    <span><?= $name ?></span>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- 默认社交链接 -->
            <a href="https://github.com/xinghe778" target="_blank" class="social-card github">
                <i class="fab fa-github"></i>
                <span>GitHub</span>
            </a>
            <a href="javascript:alert('Fuzhen88962')" class="social-card tencent">
                <i class="fa-brands fa-weixin"></i>
                <span>WeChat</span>
            </a>
            <a href="mailto:fuzhen@88.cn" class="social-card email">
                <i class="fas fa-envelope"></i>
                <span>Email Me</span>
            </a>
        <?php endif; ?>
    </div>
</section>

    <section class="section" id="friends">
        <h2>我的小伙伴 🧸</h2>
        <div class="friends-grid">
            <?php while($friend = $stmt_friends->fetch(PDO::FETCH_ASSOC)): ?>
            <a href="<?=htmlspecialchars($friend['url'])?>" target="_blank" class="friend-card">
                <div class="friend-avatar">
                    <img src="<?=htmlspecialchars($friend['avatar_url'])?>" alt="<?=$friend['name']?>" />
                </div>
                <div class="friend-info">
                    <h4><?=htmlspecialchars($friend['name'])?></h4>
                    <p><?=htmlspecialchars($friend['description'])?></p>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
    </section>
<?php 
include('footer.php');
?>