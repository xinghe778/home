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

// 添加新技能
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $percentage = (int)$_POST['percentage'];
    
    $stmt = $pdo->prepare("INSERT INTO skills (title, description, percentage) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $percentage]);
    
    // 清空缓冲区并重定向
    ob_end_clean();
    header('Location: skills.php?success=added');
    exit;
}

// 更新技能
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $percentage = (int)$_POST['percentage'];
    
    $stmt = $pdo->prepare("UPDATE skills SET title = ?, description = ?, percentage = ? WHERE id = ?");
    $stmt->execute([$title, $description, $percentage, $id]);
    
    // 清空缓冲区并重定向
    ob_end_clean();
    header('Location: skills.php?success=updated');
    exit;
}

// 删除技能
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->execute([$id]);
    
    // 清空缓冲区并重定向
    ob_end_clean();
    header('Location: skills.php?success=deleted');
    exit;
}

// 获取所有技能
$skills = $pdo->query("SELECT * FROM skills")->fetchAll(PDO::FETCH_ASSOC);

// 编辑状态变量
$edit_mode = isset($_GET['edit']);
$skill_data = [];
if ($edit_mode) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([$edit_id]);
    $skill_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 包含header.php（现在可以安全输出了）
require_once '../includes/header.php';
?>
<style>
    /* 全局样式 */
    .content-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
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
        border-bottom: 2px solid rgba(142, 197, 252, 0.15);
    }
    
    .card-title h2 {
        font-size: 1.8rem;
        color: #3a3a5a;
        margin: 0;
        font-weight: 700;
        background: linear-gradient(45deg, #8ec5fc, #ffb6c1);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
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
        box-shadow: 0 4px 12px rgba(142, 197, 252, 0.3);
    }
    
    .add-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(142, 197, 252, 0.4);
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
        padding: 15px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
        animation: fadeIn 0.5s ease;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
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
        font-size: 0.95rem;
    }
    
    .admin-table th {
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        color: white;
        font-weight: 600;
        padding: 16px 15px;
        text-align: left;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    
    .admin-table td {
        padding: 15px;
        border-bottom: 1px solid #f0f4ff;
        color: #444;
    }
    
    .admin-table tr:last-child td {
        border-bottom: none;
    }
    
    .admin-table tr:hover {
        background-color: #f8f9ff;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    /* 进度条样式 */
    .progress-container {
        width: 100%;
        height: 12px;
        background: #eee;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #8ec5fc, #ffb6c1);
        border-radius: 6px;
        transition: width 0.5s ease;
        position: relative;
    }
    
    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to right, transparent, rgba(255,255,255,0.3));
    }
    
    .progress-text {
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 5px;
        text-align: right;
        color: #6c757d;
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
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
        margin-bottom: 18px;
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
        padding: 13px 16px;
        border-radius: 10px;
        border: 1px solid #e0e7ff;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9ff;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #8ec5fc;
        box-shadow: 0 0 0 3px rgba(142, 197, 252, 0.2);
        background: white;
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    
    .percentage-input-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .percentage-input {
        flex: 1;
    }
    
    .percentage-value {
        font-weight: 600;
        color: #8ec5fc;
        min-width: 40px;
        text-align: center;
    }
    
    /* 滑块样式 */
    .slider-container {
        position: relative;
        padding-top: 15px;
    }
    
    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 8px;
        border-radius: 4px;
        background: #e0e7ff;
        outline: none;
        margin: 10px 0;
    }
    
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
    
    .slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 3px 8px rgba(0,0,0,0.3);
    }
    
    .slider::-moz-range-thumb {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8ec5fc, #ffb6c1);
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
    
    .slider::-moz-range-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 3px 8px rgba(0,0,0,0.3);
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
        padding: 12px 28px;
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
        transform: translateY(-2px);
    }
</style>

<div class="content-card">
    <div class="card-title">
        <h2>技能管理</h2>
        <button class="add-btn" id="addSkillBtn">
            <i class="fas fa-plus"></i> 添加技能
        </button>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <?php 
                switch ($_GET['success']) {
                    case 'added': echo '技能已成功添加!'; break;
                    case 'updated': echo '技能已成功更新!'; break;
                    case 'deleted': echo '技能已删除!'; break;
                }
            ?>
        </div>
    <?php endif; ?>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>技能名称</th>
                <th>描述</th>
                <th>掌握程度</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($skills as $skill): ?>
            <tr>
                <td><?= $skill['id'] ?></td>
                <td><?= htmlspecialchars($skill['title']) ?></td>
                <td><?= htmlspecialchars($skill['description']) ?></td>
                <td style="width: 200px;">
                    <div class="progress-container">
                        <div class="progress-bar" style="width: <?= $skill['percentage'] ?>%;"></div>
                    </div>
                    <div class="progress-text"><?= $skill['percentage'] ?>%</div>
                </td>
                <td>
                    <a href="?edit=<?= $skill['id'] ?>" class="action-btn edit-btn">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="?delete=<?= $skill['id'] ?>" class="action-btn delete-btn" 
                       onclick="return confirm('确定要删除此技能吗？')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- 添加/编辑表单 -->
    <div class="form-section <?= $edit_mode ? 'visible' : '' ?>" id="skillForm">
        <form method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?= $skill_data['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group full-width">
                <label for="skillName">技能名称</label>
                <input type="text" class="form-control" id="skillName" name="title" 
                       value="<?= htmlspecialchars($skill_data['title'] ?? '') ?>" 
                       placeholder="输入技能名称" required>
            </div>
            
            <div class="form-group full-width">
                <label for="skillDesc">技能描述</label>
                <textarea class="form-control" id="skillDesc" name="description" rows="4" 
                          placeholder="输入技能描述..." required><?= htmlspecialchars($skill_data['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group full-width">
                <label for="skillPercent">掌握程度</label>
                <div class="slider-container">
                    <div class="percentage-input-container">
                        <input type="range" class="slider" id="skillPercentSlider" 
                               min="0" max="100" value="<?= $skill_data['percentage'] ?? '70' ?>">
                        <div class="percentage-value" id="percentValue"><?= $skill_data['percentage'] ?? '70' ?>%</div>
                    </div>
                    <input type="hidden" class="form-control" id="skillPercent" name="percentage" 
                           value="<?= $skill_data['percentage'] ?? '70' ?>" required>
                </div>
            </div>
            
            <div class="form-actions">
                <?php if ($edit_mode): ?>
                    <button type="submit" name="update" class="admin-btn primary">
                        <i class="fas fa-sync-alt"></i> 更新技能
                    </button>
                <?php else: ?>
                    <button type="submit" name="add" class="admin-btn primary">
                        <i class="fas fa-plus"></i> 添加技能
                    </button>
                <?php endif; ?>
                <a href="skills.php" class="admin-btn secondary">
                    <i class="fas fa-times"></i> 取消
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // 修复添加技能按钮功能
    document.getElementById('addSkillBtn').addEventListener('click', function() {
        const form = document.getElementById('skillForm');
        
        // 切换表单显示状态
        form.classList.toggle('visible');
        
        if (form.classList.contains('visible')) {
            // 清空表单
            document.getElementById('skillName').value = '';
            document.getElementById('skillDesc').value = '';
            
            // 重置百分比
            const percentage = 70;
            document.getElementById('skillPercentSlider').value = percentage;
            document.getElementById('skillPercent').value = percentage;
            document.getElementById('percentValue').textContent = percentage + '%';
            
            // 滚动到表单
            form.scrollIntoView({behavior: 'smooth', block: 'start'});
        }
    });
    
    // 百分比滑块交互
    const percentSlider = document.getElementById('skillPercentSlider');
    const percentValue = document.getElementById('percentValue');
    const percentInput = document.getElementById('skillPercent');
    
    if (percentSlider) {
        percentSlider.addEventListener('input', function() {
            const value = this.value;
            percentValue.textContent = value + '%';
            percentInput.value = value;
        });
    }
    
    // 编辑模式下自动滚动到表单
    <?php if ($edit_mode): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('skillForm');
            if (form) {
                form.scrollIntoView({behavior: 'smooth', block: 'start'});
                
                // 初始化滑块位置（编辑模式）
                const percentage = <?= $skill_data['percentage'] ?? '70' ?>;
                document.getElementById('skillPercentSlider').value = percentage;
                document.getElementById('percentValue').textContent = percentage + '%';
            }
        });
    <?php endif; ?>
    
    // 页面加载时初始化滑块
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!$edit_mode): ?>
            const percentage = 70;
            document.getElementById('skillPercentSlider').value = percentage;
            document.getElementById('percentValue').textContent = percentage + '%';
        <?php endif; ?>
    });
</script>

<?php
// 包含公共底部
require_once '../includes/footer.php';
?>