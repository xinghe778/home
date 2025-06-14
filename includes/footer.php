        </div> <!-- 关闭 admin-main -->
    </div> <!-- 关闭 admin-container -->
    
    <script>
        // 主题切换功能
        const themeToggle = document.getElementById('themeToggle');
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        // 应用保存的主题
        document.documentElement.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
        
        function updateThemeIcon(theme) {
            const icon = themeToggle.querySelector('i');
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        
        // 实时预览功能（通用）
        function setupImagePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            input.addEventListener('input', function() {
                const preview = document.getElementById(previewId);
                if (this.value) {
                    if (!preview) {
                        // 创建预览元素
                        const newPreview = document.createElement('img');
                        newPreview.src = this.value;
                        newPreview.alt = '预览';
                        newPreview.id = previewId;
                        newPreview.style.width = '80px';
                        newPreview.style.height = '80px';
                        newPreview.style.objectFit = 'cover';
                        newPreview.style.borderRadius = '50%';
                        newPreview.style.marginLeft = '10px';
                        
                        // 添加到容器中
                        const container = input.parentNode;
                        container.appendChild(newPreview);
                    } else {
                        preview.src = this.value;
                    }
                } else if (preview) {
                    preview.remove();
                }
            });
        }
        
        // 表单按钮切换功能
        function toggleFormVisibility(buttonId, formId) {
            const button = document.getElementById(buttonId);
            if (!button) return;
            
            button.addEventListener('click', function() {
                const form = document.getElementById(formId);
                if (form) {
                    form.style.display = form.style.display === 'none' ? 'block' : 'none';
                    if (form.style.display === 'block') {
                        form.scrollIntoView({behavior: 'smooth'});
                    }
                }
            });
        }
        
        // 页面加载后初始化
        document.addEventListener('DOMContentLoaded', function() {
            // 初始化图片预览（可复用）
            setupImagePreview('projectImage', 'projectImagePreview');
            setupImagePreview('friendAvatar', 'friendAvatarPreview');
            
            // 初始化表单切换按钮
            toggleFormVisibility('addSkillBtn', 'skillForm');
            toggleFormVisibility('addProjectBtn', 'projectForm');
            toggleFormVisibility('addFriendBtn', 'friendForm');
            
            // 自动隐藏成功消息
            const successMessages = document.querySelectorAll('.success-message');
            successMessages.forEach(msg => {
                setTimeout(() => {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }, 3000);
            });
        });
    </script>
</body>
</html>