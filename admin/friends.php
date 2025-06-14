<?php
// 开启输出缓冲
ob_start();

require_once '../includes/auth.php';
require_once '../includes/db.php';

// 检查登录状态（必须在任何输出之前）
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// 添加新友链
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $url = $_POST['url'];
    $avatar_url = $_POST['avatar_url'];
    $description = $_POST['description'];
    
    $stmt = $pdo->prepare("INSERT INTO friends (name, url, avatar_url, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $url, $avatar_url, $description]);
    
    // 清空缓冲区并重定向
    ob_end_clean();
    header('Location: friends.php?success=added');
    exit;
}

// 更新友链
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $url = $_POST['url'];
    $avatar_url = $_POST['avatar_url'];
    $description = $_POST['description'];
    
    $stmt = $pdo->prepare("UPDATE friends SET name = ?, url = ?, avatar_url = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $url, $avatar_url, $description, $id]);
    
    // 清空缓冲区并重定向
    ob_end_clean();
    header('Location: friends.php?success=updated');
    exit;
}

// 删除友链
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM friends WHERE id = ?");
    $stmt->execute([$id]);
    
    // 清空缓冲区并重定向
    ob_end_clean();
    header('Location: friends.php?success=deleted');
    exit;
}

// 获取所有友链
$friends = $pdo->query("SELECT * FROM friends")->fetchAll(PDO::FETCH_ASSOC);

// 编辑状态变量
$edit_mode = isset($_GET['edit']);
$friend_data = [];
if ($edit_mode) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM friends WHERE id = ?");
    $stmt->execute([$edit_id]);
    $friend_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 包含header.php（现在可以安全输出了）
require_once '../includes/header.php';
?>
<style>
    /* 全局样式 */
    .content-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin: 20px auto;
        max-width: 1200px;
        position: relative;
        overflow: hidden;
    }
    
    .card-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(142, 197, 252, 0.2);
    }
    
    .card-title h2 {
        font-size: 1.8rem;
        color: #3a3a5a;
        margin: 0;
    }
    
    /* 添加按钮样式 */
    .add-btn {
        padding: 10px 20px;
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(142, 197, 252, 0.3);
    }
    
    .add-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(142, 197, 252, 0.4);
        background: linear-gradient(135deg, #7fb3e8, #e5a4ae);
    }
    
    .add-btn:active {
        transform: translateY(1px);
    }
    
    .add-btn i {
        font-size: 1rem;
    }
    
    /* 成功消息 */
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* 表格样式 */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .admin-table th {
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        color: white;
        font-weight: 600;
        padding: 15px;
        text-align: left;
    }
    
    .admin-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #555;
    }
    
    .admin-table tr:last-child td {
        border-bottom: none;
    }
    
    .admin-table tr:hover {
        background-color: #f8f9ff;
    }
    
    /* 操作按钮 */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin: 0 5px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .edit-btn {
        background: #e3f2fd;
        color: #2196f3;
    }
    
    .delete-btn {
        background: #ffebee;
        color: #f44336;
    }
    
    .edit-btn:hover {
        background: #bbdefb;
        transform: scale(1.1);
    }
    
    .delete-btn:hover {
        background: #ffcdd2;
        transform: scale(1.1);
    }
    
    /* 头像样式 */
    .friend-avatar-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e0e7ff;
        transition: all 0.3s ease;
    }
    
    .friend-avatar-img:hover {
        transform: scale(1.1);
        border-color: #8ec5fc;
        box-shadow: 0 5px 15px rgba(142, 197, 252, 0.3);
    }
    
    /* 表单区域 */
    .form-section {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        padding: 25px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin-top: 25px;
        animation: fadeIn 0.4s ease-out;
        display: none; /* 默认隐藏 */
    }
    
    .form-section.visible {
        display: grid;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group.full-width {
        grid-column: span 2;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #8ec5fc;
        box-shadow: 0 0 0 3px rgba(142, 197, 252, 0.2);
    }
    
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    /* 头像预览 */
    .avatar-preview-container {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 10px;
    }
    
    .avatar-preview {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e0e0e0;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .avatar-preview:hover {
        transform: scale(1.1);
        border-color: #8ec5fc;
    }
    
    .avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #f0f4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #8ec5fc;
        color: #8ec5fc;
    }
    
    /* 表单操作按钮 */
    .form-actions {
        grid-column: span 2;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 10px;
    }
    
    .admin-btn {
        padding: 12px 25px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .admin-btn.primary {
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        color: white;
    }
    
    .admin-btn.primary:hover {
        background: linear-gradient(135deg, #7fb3e8, #e5a4ae);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(142, 197, 252, 0.4);
    }
    
    .admin-btn.secondary {
        background: #f0f4ff;
        color: #6c757d;
    }
    
    .admin-btn.secondary:hover {
        background: #e4e7eb;
    }
</style>

<div class="content-card">
    <div class="card-title">
        <h2>友情链接</h2>
        <button class="add-btn" id="addFriendBtn">
            <i class="fas fa-plus"></i> 添加友链
        </button>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <?php 
                switch ($_GET['success']) {
                    case 'added': echo '友链已成功添加!'; break;
                    case 'updated': echo '友链已成功更新!'; break;
                    case 'deleted': echo '友链已删除!'; break;
                }
            ?>
        </div>
    <?php endif; ?>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>网址</th>
                <th>头像</th>
                <th>描述</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($friends as $friend): ?>
            <tr>
                <td><?= $friend['id'] ?></td>
                <td><?= htmlspecialchars($friend['name']) ?></td>
                <td><a href="<?= $friend['url'] ?>" target="_blank"><?= htmlspecialchars(substr($friend['url'], 0, 30)) ?>...</a></td>
                <td>
                    <?php if (!empty($friend['avatar_url'])): ?>
                        <img src="<?= $friend['avatar_url'] ?>" alt="头像" class="friend-avatar-img">
                    <?php else: ?>
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars(substr($friend['description'], 0, 20)) ?>...</td>
                <td>
                    <a href="?edit=<?= $friend['id'] ?>" class="action-btn edit-btn">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="?delete=<?= $friend['id'] ?>" class="action-btn delete-btn" 
                       onclick="return confirm('确定要删除此友链吗？')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- 添加/编辑表单 -->
    <div class="form-section <?= $edit_mode ? 'visible' : '' ?>" id="friendForm">
        <form method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?= $friend_data['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="friendName">友链名称</label>
                <input type="text" class="form-control" id="friendName" name="name" 
                       value="<?= htmlspecialchars($friend_data['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group full-width">
                <label for="friendAvatar">头像URL</label>
                <div class="avatar-preview-container">
                    <input type="text" class="form-control" id="friendAvatar" name="avatar_url" 
                           value="<?= htmlspecialchars($friend_data['avatar_url'] ?? '') ?>" 
                           placeholder="输入图片URL地址" required>
                    <div id="avatarPreviewContainer">
                        <?php if (!empty($friend_data['avatar_url'])): ?>
                            <img src="<?= htmlspecialchars($friend_data['avatar_url']) ?>" alt="预览" class="avatar-preview" id="friendAvatarPreview">
                        <?php else: ?>
                            <div class="avatar-placeholder" id="avatarPlaceholder">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group full-width">
                <label for="friendUrl">友链网址</label>
                <input type="url" class="form-control" id="friendUrl" name="url" 
                       value="<?= htmlspecialchars($friend_data['url'] ?? '') ?>" placeholder="https://example.com" required>
            </div>
            
            <div class="form-group full-width">
                <label for="friendDesc">友链描述</label>
                <textarea class="form-control" id="friendDesc" name="description" rows="3" 
                          placeholder="输入友链描述信息..." required><?= htmlspecialchars($friend_data['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-actions">
                <?php if ($edit_mode): ?>
                    <button type="submit" name="update" class="admin-btn primary">
                        <i class="fas fa-sync-alt"></i> 更新友链
                    </button>
                <?php else: ?>
                    <button type="submit" name="add" class="admin-btn primary">
                        <i class="fas fa-plus"></i> 添加友链
                    </button>
                <?php endif; ?>
                <a href="friends.php" class="admin-btn secondary">
                    <i class="fas fa-times"></i> 取消
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // 修复添加友链按钮功能
    document.getElementById('addFriendBtn').addEventListener('click', function() {
        const form = document.getElementById('friendForm');
        
        // 切换表单显示状态
        form.classList.toggle('visible');
        
        if (form.classList.contains('visible')) {
            // 清空表单
            document.getElementById('friendName').value = '';
            document.getElementById('friendAvatar').value = '';
            document.getElementById('friendUrl').value = '';
            document.getElementById('friendDesc').value = '';
            
            // 重置头像预览
            resetAvatarPreview();
            
            // 滚动到表单
            form.scrollIntoView({behavior: 'smooth', block: 'start'});
        }
    });
    
    // 头像实时预览
    document.getElementById('friendAvatar')?.addEventListener('input', function() {
        updateAvatarPreview(this.value);
    });
    
    // 更新头像预览
    function updateAvatarPreview(url) {
        const previewContainer = document.getElementById('avatarPreviewContainer');
        
        if (url) {
            // 创建预览图片
            let preview = document.getElementById('friendAvatarPreview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'friendAvatarPreview';
                preview.className = 'avatar-preview';
                preview.alt = '预览';
                previewContainer.innerHTML = '';
                previewContainer.appendChild(preview);
            }
            preview.src = url;
        } else {
            // 显示默认占位符
            resetAvatarPreview();
        }
    }
    
    // 重置头像预览
    function resetAvatarPreview() {
        const previewContainer = document.getElementById('avatarPreviewContainer');
        previewContainer.innerHTML = `
            <div class="avatar-placeholder" id="avatarPlaceholder">
                <i class="fas fa-image"></i>
            </div>
        `;
    }
    
    // 编辑模式下自动滚动到表单
    <?php if ($edit_mode): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('friendForm');
            if (form) {
                form.scrollIntoView({behavior: 'smooth', block: 'start'});
            }
        });
    <?php endif; ?>
    
    // 页面加载时初始化头像预览
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('friendAvatar');
        if (avatarInput && avatarInput.value) {
            updateAvatarPreview(avatarInput.value);
        }
    });
</script>

<?php
// 包含公共底部
require_once '../includes/footer.php';
?>