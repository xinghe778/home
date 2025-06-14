<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// 初始化错误消息
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 获取并验证表单数据
        $site_name = trim($_POST['site_name'] ?? '');
        $site_author = trim($_POST['site_author'] ?? '');
        $author_qq = trim($_POST['author_qq'] ?? '');
        $icp_number = trim($_POST['icp_number'] ?? '');
        $police_icp = trim($_POST['police_icp'] ?? '');
        
        // 验证必填字段
        if (empty($site_name) || empty($site_author)) {
            throw new Exception("网站名称和作者为必填项");
        }
        
        // 处理社交链接
        $social_links = [];
        if (!empty($_POST['social_name']) && is_array($_POST['social_name'])) {
            foreach ($_POST['social_name'] as $key => $name) {
                $url = $_POST['social_url'][$key] ?? '';
                $icon = $_POST['social_icon'][$key] ?? '';
                
                if (!empty($name) && !empty($url)) {
                    $social_links[] = [
                        'name' => htmlspecialchars(trim($name)),
                        'url' => filter_var(trim($url), FILTER_VALIDATE_URL) ? trim($url) : '#',
                        'icon' => htmlspecialchars(trim($icon))
                    ];
                }
            }
        }
        
        // 转换为JSON格式
        $social_links_json = json_encode($social_links, JSON_UNESCAPED_UNICODE);
        
        // 如果没有现有设置则插入新记录
        if (!$settings) {
            $stmt = $pdo->prepare("INSERT INTO settings 
                (site_name, site_author, author_qq, icp_number, police_icp, social_links) 
                VALUES (?, ?, ?, ?, ?, ?)");
                
            $stmt->execute([
                $site_name, 
                $site_author, 
                $author_qq, 
                $icp_number, 
                $police_icp, 
                $social_links_json
            ]);
            
            // 获取新插入的记录ID
            $settings_id = $pdo->lastInsertId();
        } else {
            // 更新现有设置
            $stmt = $pdo->prepare("UPDATE settings SET 
                site_name = ?, 
                site_author = ?, 
                author_qq = ?, 
                icp_number = ?, 
                police_icp = ?, 
                social_links = ? 
                WHERE id = ?");
                
            $stmt->execute([
                $site_name, 
                $site_author, 
                $author_qq, 
                $icp_number, 
                $police_icp, 
                $social_links_json,
                $settings['id']
            ]);
        }
        
        // 重定向到成功页面
        header('Location: setting.php?success=1');
        exit;
        
    } catch (PDOException $e) {
        // 记录数据库错误
        error_log("数据库错误: " . $e->getMessage());
        $error = "数据库写入失败: " . $e->getMessage();
    } catch (Exception $e) {
        // 处理其他错误
        $error = $e->getMessage();
    }
}

// 获取当前设置
try {
    $settings_stmt = $pdo->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
    $settings = $settings_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$settings) {
        $pdo->query("INSERT INTO settings (site_name) VALUES ('我的个人网站')");
        $settings_stmt = $pdo->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
        $settings = $settings_stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("获取设置失败: " . $e->getMessage());
    $error = "获取设置失败: " . $e->getMessage();
}

// 解析社交链接
$social_links = [];
if (!empty($settings['social_links'])) {
    $decoded = json_decode($settings['social_links'], true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $social_links = $decoded;
    } else {
        error_log("JSON解析错误: " . json_last_error_msg());
    }
}

// 如果没有社交链接，添加一个空项
if (empty($social_links)) {
    $social_links[] = ['name' => '', 'url' => '', 'icon' => ''];
}

// 包含头部文件
require_once '../includes/header.php';
?>

<style>
    /* ================ 全局样式 ================ */
    .settings-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0;
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.88), rgba(245, 247, 250, 0.88));
        backdrop-filter: blur(15px);
        border: 1px solid rgba(142, 197, 252, 0.2);
        box-shadow: 
            0 15px 50px rgba(0, 0, 0, 0.1),
            0 0 0 1px rgba(142, 197, 252, 0.05);
    }
    
    .settings-header {
        padding: 35px 40px 25px;
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .settings-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 150%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(30deg);
    }
    
    .settings-header h1 {
        font-size: 2.8rem;
        margin: 0 0 10px 0;
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 15px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .settings-header h1 i {
        font-size: 2.2rem;
        background: rgba(255, 255, 255, 0.2);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    
    .settings-header p {
        font-size: 1.15rem;
        max-width: 700px;
        margin: 0;
        opacity: 0.9;
        position: relative;
        line-height: 1.6;
    }
    
    /* ================ 表单区域 ================ */
    .settings-form {
        padding: 35px 40px;
    }
    
    .form-section {
        padding: 30px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 18px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(142, 197, 252, 0.1);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    
    .form-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
    }
    
    .section-title {
        font-size: 1.5rem;
        margin-top: 0;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(142, 197, 252, 0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        color: #3a3a5a;
    }
    
    .section-title i {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(142, 197, 252, 0.15);
        color: #8ec5fc;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }
    
    .form-group {
        margin-bottom: 22px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #3a3a5a;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-group label i {
        width: 24px;
        text-align: center;
        color: #8ec5fc;
        font-size: 1.1rem;
    }
    
    .form-control {
        width: 100%;
        padding: 16px 20px;
        border-radius: 14px;
        border: 1px solid #e0e7ff;
        font-size: 1.05rem;
        transition: all 0.3s ease;
        background: rgba(248, 249, 255, 0.8);
        box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.03);
    }
    
    .form-control:focus {
        outline: none;
        border-color: #8ec5fc;
        box-shadow: 
            0 0 0 3px rgba(142, 197, 252, 0.25),
            inset 0 1px 4px rgba(0, 0, 0, 0.05);
        background: white;
    }
    
    /* ================ 社交链接部分 ================ */
    .social-links-section {
        background: rgba(142, 197, 252, 0.06);
        padding: 30px;
        border-radius: 18px;
        margin: 35px 0;
        border: 1px dashed rgba(142, 197, 252, 0.2);
    }
    
    .social-links-section .section-title i {
        background: rgba(255, 182, 193, 0.15);
        color: #ffb6c1;
    }
    
    .social-link-row {
        display: grid;
        grid-template-columns: 28% 42% 25% 5%;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
        animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .social-link-row:last-child {
        margin-bottom: 0;
    }
    
    .social-link-row .form-control {
        padding: 13px 15px;
        border-radius: 12px;
    }
    
    .remove-social-btn {
        background: #ff6b6b;
        color: white;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(255, 107, 107, 0.2);
    }
    
    .remove-social-btn:hover {
        background: #ff5252;
        transform: scale(1.08);
        box-shadow: 0 6px 15px rgba(255, 107, 107, 0.3);
    }
    
    .add-social-btn {
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 14px;
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
        margin-top: 20px;
        font-size: 1.05rem;
        box-shadow: 0 6px 15px rgba(142, 197, 252, 0.3);
    }
    
    .add-social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(142, 197, 252, 0.4);
    }
    
    .add-social-btn:active {
        transform: translateY(1px);
    }
    
    /* ================ 操作按钮 ================ */
    .form-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 40px;
    }
    
    .save-btn {
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        color: white;
        border: none;
        padding: 18px 50px;
        border-radius: 16px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1.15rem;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: inline-flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 8px 25px rgba(142, 197, 252, 0.35);
    }
    
    .save-btn:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 35px rgba(142, 197, 252, 0.45);
    }
    
    .save-btn:active {
        transform: translateY(2px);
    }
    
    .reset-btn {
        background: rgba(240, 244, 255, 0.8);
        color: #6c757d;
        border: 1px solid rgba(142, 197, 252, 0.3);
        padding: 18px 40px;
        border-radius: 16px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1.15rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .reset-btn:hover {
        background: rgba(224, 230, 255, 0.8);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    /* ================ 成功消息 ================ */
    .floating-notification {
        position: fixed;
        top: 30px;
        right: 30px;
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        color: white;
        padding: 20px 30px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 1000;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.5s ease, fadeOut 0.5s ease 2.5s forwards;
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-50px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; visibility: hidden; }
    }
    
    .floating-notification i {
        font-size: 1.8rem;
    }
    
    .notification-content h3 {
        margin: 0 0 5px 0;
        font-size: 1.3rem;
    }
    
    .notification-content p {
        margin: 0;
        opacity: 0.9;
        font-size: 1rem;
    }
    
    /* ================ 响应式设计 ================ */
    @media (max-width: 768px) {
        .settings-container {
            margin: 15px;
            border-radius: 16px;
        }
        
        .settings-header {
            padding: 25px;
        }
        
        .settings-header h1 {
            font-size: 2.2rem;
        }
        
        .settings-header h1 i {
            width: 55px;
            height: 55px;
        }
        
        .settings-form {
            padding: 25px;
        }
        
        .social-link-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .remove-social-btn {
            width: 100%;
            margin-top: 5px;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 15px;
        }
        
        .save-btn, .reset-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<?php if (isset($_GET['success'])): ?>
    <div class="floating-notification">
        <i class="fas fa-check-circle"></i>
        <div class="notification-content">
            <h3>设置已保存!</h3>
            <p>您的网站配置已成功更新。</p>
        </div>
    </div>
<?php endif; ?>

<div class="settings-container">
    <div class="settings-header">
        <h1><i class="fas fa-cog"></i> 网站设置中心</h1>
        <p>在此管理您的网站基本信息、备案信息和社交链接。所有更改将实时生效。</p>
    </div>
    
    <form class="settings-form" method="POST">
        <!-- 基本信息部分 -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-info-circle"></i> 基本信息</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="site_name"><i class="fas fa-globe"></i> 网站名称</label>
                    <input type="text" class="form-control" id="site_name" name="site_name" 
                           value="<?= htmlspecialchars($settings['site_name']) ?>" 
                           placeholder="请输入网站名称" required>
                </div>
                
                <div class="form-group">
                    <label for="site_author"><i class="fas fa-user"></i> 网站作者</label>
                    <input type="text" class="form-control" id="site_author" name="site_author" 
                           value="<?= htmlspecialchars($settings['site_author']) ?>" 
                           placeholder="请输入作者名称" required>
                </div>
                
                <div class="form-group">
                    <label for="author_qq"><i class="fab fa-qq"></i> 作者QQ</label>
                    <input type="text" class="form-control" id="author_qq" name="author_qq" 
                           value="<?= htmlspecialchars($settings['author_qq']) ?>" 
                           placeholder="请输入QQ号码">
                </div>
            </div>
        </div>
        
        <!-- 备案信息部分 -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-certificate"></i> 备案信息</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="icp_number"><i class="fas fa-id-card"></i> 网站备案号</label>
                    <input type="text" class="form-control" id="icp_number" name="icp_number" 
                           value="<?= htmlspecialchars($settings['icp_number']) ?>" 
                           placeholder="例如：京ICP备12345678号">
                </div>
                
                <div class="form-group">
                    <label for="police_icp"><i class="fas fa-shield-alt"></i> 公安备案号</label>
                    <input type="text" class="form-control" id="police_icp" name="police_icp" 
                           value="<?= htmlspecialchars($settings['police_icp']) ?>" 
                           placeholder="例如：京公网安备11010502030123号">
                </div>
            </div>
        </div>
        
        <!-- 社交链接部分 -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-share-alt"></i> 社交链接</h3>
            
            <div class="social-links-section">
                <div id="socialLinksContainer">
                    <?php foreach ($social_links as $index => $link): ?>
                    <div class="social-link-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="social_name[]" 
                                   placeholder="平台名称" value="<?= htmlspecialchars($link['name']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <input type="url" class="form-control" name="social_url[]" 
                                   placeholder="https://example.com" value="<?= htmlspecialchars($link['url']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <input type="text" class="form-control" name="social_icon[]" 
                                   placeholder="FontAwesome图标" value="<?= htmlspecialchars($link['icon']) ?>">
                        </div>
                        
                        <button type="button" class="remove-social-btn" onclick="removeSocialLink(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="button" class="add-social-btn" id="addSocialLink">
                    <i class="fas fa-plus"></i> 添加社交链接
                </button>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="save-btn">
                <i class="fas fa-save"></i> 保存设置
            </button>
            <button type="reset" class="reset-btn">
                <i class="fas fa-redo"></i> 重置更改
            </button>
        </div>
    </form>
</div>

<script>
    // 添加社交链接
    document.getElementById('addSocialLink').addEventListener('click', function() {
        const container = document.getElementById('socialLinksContainer');
        const newRow = document.createElement('div');
        newRow.className = 'social-link-row';
        newRow.innerHTML = `
            <div class="form-group">
                <input type="text" class="form-control" name="social_name[]" placeholder="平台名称">
            </div>
            <div class="form-group">
                <input type="url" class="form-control" name="social_url[]" placeholder="https://example.com">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="social_icon[]" placeholder="FontAwesome图标">
            </div>
            <button type="button" class="remove-social-btn" onclick="removeSocialLink(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newRow);
        
        // 滚动到新添加的行
        newRow.scrollIntoView({behavior: 'smooth', block: 'nearest'});
    });
    
    // 删除社交链接
    function removeSocialLink(button) {
        const row = button.closest('.social-link-row');
        if (row && document.querySelectorAll('.social-link-row').length > 1) {
            // 添加移除动画
            row.style.opacity = '0';
            row.style.transform = 'translateX(50px)';
            setTimeout(() => row.remove(), 300);
        } else if (row) {
            // 如果是最后一行，清空输入框
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => input.value = '');
        }
    }
    
    // 表单重置确认
    document.querySelector('.reset-btn').addEventListener('click', function(e) {
        if (!confirm('确定要重置所有更改吗？未保存的更改将丢失。')) {
            e.preventDefault();
        }
    });
    
    // 自动关闭成功消息
    <?php if (isset($_GET['success'])): ?>
        setTimeout(() => {
            const notification = document.querySelector('.floating-notification');
            if (notification) {
                notification.style.animation = 'fadeOut 0.5s ease forwards';
                setTimeout(() => notification.remove(), 500);
            }
        }, 2500);
    <?php endif; ?>
</script>

<?php
require_once '../includes/footer.php';
?>