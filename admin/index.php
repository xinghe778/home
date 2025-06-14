<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/header.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// 获取统计数据
$skills_count = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$projects_count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$friends_count = $pdo->query("SELECT COUNT(*) FROM friends")->fetchColumn();

// 获取最近修改数据
$recent_skills = $pdo->query("SELECT * FROM skills ORDER BY updated_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$recent_projects = $pdo->query("SELECT * FROM projects ORDER BY updated_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* --------------------- 仪表盘全局样式 --------------------- */
    .dashboard-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* --------------------- 统计卡片样式 --------------------- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .stats-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.85), rgba(245, 247, 250, 0.85));
        border-radius: 18px;
        overflow: hidden;
        padding: 25px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(142, 197, 252, 0.05);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 1;
        border: 1px solid rgba(142, 197, 252, 0.15);
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        z-index: -1;
    }
    
    .stats-card:nth-child(1)::before { background: linear-gradient(90deg, #8ec5fc, #80aefc); }
    .stats-card:nth-child(2)::before { background: linear-gradient(90deg, #ffd166, #ffc341); }
    .stats-card:nth-child(3)::before { background: linear-gradient(90deg, #ffb6c1, #ff9daf); }
    
    .stats-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(142, 197, 252, 0.1);
    }
    
    .stats-icon {
        width: 75px;
        height: 75px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .stats-card:nth-child(1) .stats-icon {
        background: linear-gradient(135deg, #8ec5fc, #80aefc);
        color: white;
    }
    
    .stats-card:nth-child(2) .stats-icon {
        background: linear-gradient(135deg, #ffd166, #ffc341);
        color: white;
    }
    
    .stats-card:nth-child(3) .stats-icon {
        background: linear-gradient(135deg, #ffb6c1, #ff9daf);
        color: white;
    }
    
    .stats-info {
        flex: 1;
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        background: linear-gradient(45deg, #3a3a5a, #1a1a2e);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .stats-title {
        font-size: 1.1rem;
        color: #6c757d;
        margin-top: 5px;
        font-weight: 500;
    }
    
    /* --------------------- 内容卡片样式 --------------------- */
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .content-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.85), rgba(245, 247, 250, 0.85));
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(142, 197, 252, 0.15);
    }
    
    .card-title {
        padding: 25px 25px 15px;
        border-bottom: 1px solid rgba(142, 197, 252, 0.15);
    }
    
    .card-title h2 {
        margin: 0;
        font-size: 1.6rem;
        font-weight: 700;
        background: linear-gradient(45deg, #8ec5fc, #ffb6c1);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    /* --------------------- 活动列表样式 --------------------- */
    .activity-list {
        padding: 20px 25px 25px;
    }
    
    .activity-item {
        display: flex;
        gap: 15px;
        padding: 18px 0;
        border-bottom: 1px solid rgba(142, 197, 252, 0.1);
        transition: all 0.3s ease;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-item:hover {
        transform: translateX(5px);
        background: rgba(142, 197, 252, 0.03);
        border-radius: 10px;
        padding: 18px 15px;
    }
    
    .activity-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
        background: rgba(142, 197, 252, 0.15);
        color: #8ec5fc;
    }
    
    .activity-icon.add {
        background: rgba(76, 175, 80, 0.15);
        color: #4CAF50;
    }
    
    .activity-icon.edit {
        background: rgba(33, 150, 243, 0.15);
        color: #2196F3;
    }
    
    .activity-icon.delete {
        background: rgba(244, 67, 54, 0.15);
        color: #F44336;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 5px;
        color: #3a3a5a;
    }
    
    .activity-time {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    /* --------------------- 图表容器样式 --------------------- */
    .chart-container {
        height: 300px;
        padding: 20px;
    }
    
    /* --------------------- 最近修改面板样式 --------------------- */
    .recent-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 25px;
    }
    
    .recent-panel {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.85), rgba(245, 247, 250, 0.85));
        border-radius: 18px;
        overflow: hidden;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(142, 197, 252, 0.15);
    }
    
    .recent-panel h3 {
        margin-top: 0;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(142, 197, 252, 0.15);
        font-size: 1.3rem;
        color: #3a3a5a;
    }
    
    .recent-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed rgba(142, 197, 252, 0.15);
    }
    
    .recent-item:last-child {
        border-bottom: none;
    }
    
    .recent-name {
        font-weight: 500;
        color: #3a3a5a;
    }
    
    .recent-time {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* --------------------- 响应式调整 --------------------- */
    @media (max-width: 1100px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .recent-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-card {
            padding: 20px;
        }
        
        .card-title {
            padding: 20px 20px 12px;
        }
        
        .activity-list {
            padding: 15px 20px;
        }
    }
</style>

<div class="dashboard-container">
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-code"></i>
            </div>
            <div class="stats-info">
                <div class="stats-number"><?= $skills_count ?></div>
                <div class="stats-title">技能数量</div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="stats-info">
                <div class="stats-number"><?= $projects_count ?></div>
                <div class="stats-title">项目数量</div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-info">
                <div class="stats-number"><?= $friends_count ?></div>
                <div class="stats-title">友链数量</div>
            </div>
        </div>
    </div>
    
    <div class="content-grid">
        <!-- 活动卡片 -->
        <div class="content-card">
            <div class="card-title">
                <h2>最近活动</h2>
            </div>
            
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon add">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">添加了新技能：PHP开发</div>
                        <div class="activity-time">2小时前</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon edit">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">更新了项目：AI助手系统</div>
                        <div class="activity-time">1天前</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon delete">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">删除了友链：旧版博客</div>
                        <div class="activity-time">2天前</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon add">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">添加了新项目：响应式仪表盘</div>
                        <div class="activity-time">3天前</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon edit">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">更新了技能：JavaScript掌握程度</div>
                        <div class="activity-time">4天前</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 资源使用卡片 -->
        <div class="content-card">
            <div class="card-title">
                <h2>资源使用情况</h2>
            </div>
            <div class="chart-container">
                <canvas id="resourceChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- 最近修改部分 -->
    <div class="recent-grid">
        <div class="recent-panel">
            <h3>最近更新的技能</h3>
            <?php foreach ($recent_skills as $skill): ?>
                <div class="recent-item">
                    <div class="recent-name"><?= htmlspecialchars($skill['title']) ?> (<?= $skill['percentage'] ?>%)</div>
                    <div class="recent-time"><?= date('m/d H:i', strtotime($skill['updated_at'])) ?></div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($recent_skills)): ?>
                <div class="recent-item">
                    <div class="recent-name">暂无最近修改的技能</div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="recent-panel">
            <h3>最近更新的项目</h3>
            <?php foreach ($recent_projects as $project): ?>
                <div class="recent-item">
                    <div class="recent-name"><?= htmlspecialchars($project['name']) ?></div>
                    <div class="recent-time"><?= date('m/d H:i', strtotime($project['updated_at'])) ?></div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($recent_projects)): ?>
                <div class="recent-item">
                    <div class="recent-name">暂无最近修改的项目</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 初始化资源使用图表
    const resourceCtx = document.getElementById('resourceChart').getContext('2d');
    const resourceChart = new Chart(resourceCtx, {
        type: 'doughnut',
        data: {
            labels: ['数据库', '图片存储', '代码文件', '缓存'],
            datasets: [{
                data: [35, 20, 25, 20],
                backgroundColor: [
                    'rgba(142, 197, 252, 0.8)',
                    'rgba(255, 214, 102, 0.8)',
                    'rgba(255, 182, 193, 0.8)',
                    'rgba(186, 230, 174, 0.8)'
                ],
                borderColor: [
                    'rgba(142, 197, 252, 1)',
                    'rgba(255, 214, 102, 1)',
                    'rgba(255, 182, 193, 1)',
                    'rgba(186, 230, 174, 1)'
                ],
                borderWidth: 1,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 25,
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed}%`;
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
    
    // 主题切换功能
    document.getElementById('themeToggle').addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            document.documentElement.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        }
    });
    
    // 页面加载时应用保存的主题
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    });
</script>

<?php
// 包含公共底部
require_once '../includes/footer.php';
?>