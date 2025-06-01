
      <footer class="site-footer">
    <div class="footer-content">
        <div class="footer-left">
            <p class="copyright">CopyRight
                &copy;2024-<?php echo date('Y') ?>
                <span class="author">xinghe's World</span>
            </p>
        </div>
        
        <div class="footer-center">
            <p class="license-info">
                <a href="https://beian.mit.gov.com" class="beian-link">
                    <i class="fas fa-shield-alt"></i>
                    <span class="beian-text">皖ICP备2024037265号</span>
                </a>
                <a href="https://beian.mps.gov.cn/#/query/webSearch?code=34150202000410" 
                   class="gongan-link" 
                   target="_blank" 
                   rel="noreferrer">
                    <img src="gonganbeian.png" alt="公安备案图标" class="gongan-icon">
                    <span class="gongan-text">皖公网安备34150202000410号</span>
                </a>
            </p>
        </div>
        
        <div class="footer-right">
            <p class="footer-emoji">🐾</p>
        </div>
    </div>
    </footer>

    <script>
        // 打字机效果
        const texts = ["前端开发者", "自由追逐者", "传统文化者", "生活观察者"];
        let count = 0;
        let index = 0;
        let currentText = '';
        let letter = '';

        function type() {
            if (count === texts.length) {
                count = 0;
            }
            currentText = texts[count];
            letter = currentText.slice(0, ++index);

            document.getElementById('typewriter').textContent = letter;

            if (letter.length === currentText.length) {
                count++;
                index = 0;
                setTimeout(type, 2000);
            } else {
                setTimeout(type, 200);
            }
        }

        window.addEventListener('DOMContentLoaded', type);

        // 主题切换
        document.getElementById('themeToggle').addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            if (currentTheme === 'dark') {
                document.documentElement.removeAttribute('data-theme');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        });
        // 自动检测 + 用户偏好记忆版本
        function applyTheme(theme) {
        if (theme === 'dark') {
           document.documentElement.setAttribute('data-theme', 'dark');
          } else {
            document.documentElement.removeAttribute('data-theme');
           }
         }
         function autoSwitchTheme() {
         const hour = new Date().getHours();
         const isDayTime = hour >= 6 && hour < 20;
         const userTheme = localStorage.getItem('userTheme');

          if (userTheme) {
          // 如果用户有偏好，优先使用用户设置
            applyTheme(userTheme);
          } else {
           // 否则根据时间自动切换
           applyTheme(isDayTime ? 'light' : 'dark');
          }
        }   
       // 初始化
        window.addEventListener('DOMContentLoaded', () => {
            autoSwitchTheme();
             type();
           });
        // 粒子背景
        const canvas = document.getElementById('particles-canvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];

        function resizeCanvas() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.size = Math.random() * 3 + 1;
                this.speedX = Math.random() * 0.5 - 0.25;
                this.speedY = Math.random() * 0.5 + 0.5;
                this.opacity = Math.random();
            }

            update() {
                this.y -= this.speedY;
                this.x += this.speedX;
                if (this.y < 0) {
                    this.y = height;
                    this.x = Math.random() * width;
                }
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
                ctx.fill();
            }
        }

        function initParticles() {
            particles = [];
            for (let i = 0; i < 100; i++) {
                particles.push(new Particle());
            }
        }

        function animateParticles() {
            ctx.clearRect(0, 0, width, height);
            for (let particle of particles) {
                particle.update();
                particle.draw();
            }
            requestAnimationFrame(animateParticles);
        }

        window.addEventListener('resize', () => {
            resizeCanvas();
            initParticles();
        });

        resizeCanvas();
        initParticles();
        animateParticles();

        // 动态生成气泡
        function createBubble() {
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            bubble.style.left = Math.random() * 100 + '%';
            bubble.style.width = bubble.style.height = `${Math.random() * 20 + 10}px`;
            bubble.style.animationDuration = `${Math.random() * 5 + 5}s`;
            document.body.appendChild(bubble);

            // 8秒后移除
            setTimeout(() => bubble.remove(), 8000);
        }

        setInterval(createBubble, 500);

        // 点赞按钮交互
        function toggleLike(el) {
            el.classList.toggle('active');
            const icon = el.querySelector('i');
            if (el.classList.contains('active')) {
                icon.classList.replace('far', 'fas');
            } else {
                icon.classList.replace('fas', 'far');
            }
        }

        // 鼠标视差效果
        document.addEventListener('mousemove', e => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;

            document.querySelectorAll('.parallax').forEach(layer => {
                const speed = layer.getAttribute('data-parallax');
                layer.style.transform = 
                    `translateX(${(x - 0.5) * speed * 2}px) 
                     translateY(${(y - 0.5) * speed}px)`;
            });
        });

        // 项目卡片添加视差
        document.querySelectorAll('.project-card').forEach(card => {
            card.setAttribute('data-parallax', '5');
            card.classList.add('parallax');
        });
    </script>
</body>
</html>