-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2025-06-15 04:24:06
-- 服务器版本： 5.7.44-log
-- PHP 版本： 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `home1`
--

-- --------------------------------------------------------

--
-- 表的结构 `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `avatar_url` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `friends`
--

INSERT INTO `friends` (`id`, `name`, `url`, `avatar_url`, `description`, `created_at`) VALUES
(1, '浅蓝的小窝', 'https://xiaoqianlan.com/', 'https://xiaoqianlan.com/images/avatar.jpg', '一名沉迷轻小说漫画动漫二次元游戏的自宅警备员，热爱Tech和ACG文化，生活中的一只小透明。\r\n\r\n希望能与你在比特之海的繁星之下相见！', '2025-06-14 19:35:23'),
(2, '千山云', 'https://cloud.vanyue.tech/', 'https://bbs.spanstar.cn/logo.png', '千山云计算为您提供安全可靠、高性价比的全球云计算解决方案！', '2025-06-14 19:42:17');

-- --------------------------------------------------------

--
-- 表的结构 `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `project_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `image_url`, `project_url`, `created_at`, `updated_at`) VALUES
(2, 'AI助手', '帮助用户快速回复访问者问题,解决消息滞后.', 'assets/chutian.png', '', '2025-06-14 19:49:28', '2025-06-14 20:23:23'),
(3, 'Qianshan财务系统', '打造轻量级记账系统', 'https://admin.spanstar.cn/dashboard.png', '', '2025-06-14 19:50:19', '2025-06-14 19:50:19'),
(4, '个人主页V2', '个人主页项目,展示您的个人风采', 'assets/index.png', 'https://github.com/xinghe778/home', '2025-06-14 19:51:41', '2025-06-14 20:23:34');

-- --------------------------------------------------------

--
-- 表的结构 `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL DEFAULT '我的个人网站',
  `site_author` varchar(255) NOT NULL DEFAULT '管理员',
  `author_qq` varchar(20) DEFAULT NULL,
  `icp_number` varchar(50) DEFAULT NULL,
  `police_icp` varchar(100) DEFAULT NULL,
  `social_links` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_author`, `author_qq`, `icp_number`, `police_icp`, `social_links`, `created_at`, `updated_at`) VALUES
(4, 'Xinghe&^小屋', '星河', '2931468138', '皖ICP备2024037265号', '皖公网安备34150202000410号', '[]', '2025-06-14 19:59:23', '2025-06-14 19:59:23'),
(7, 'Xinghe&^小屋', '星河', '2931468138', '皖ICP备2024037265号', '皖公网安备34150202000410号', '[{\"name\":\"github\",\"url\":\"https:\\/\\/111.111.111\",\"icon\":\"fab fa-github\"},{\"name\":\"WeChat\",\"url\":\"https:\\/\\/111.111.111\",\"icon\":\"fa-brands fa-weixin\"},{\"name\":\"twitter\",\"url\":\"https:\\/\\/111.111.111\",\"icon\":\"fab fa-twitter\"},{\"name\":\"Netease Cloud\",\"url\":\"https:\\/\\/111.111.111\",\"icon\":\"fas fa-music\"},{\"name\":\"email\",\"url\":\"https:\\/\\/111.111.111\",\"icon\":\"fas fa-envelope\"},{\"name\":\"website\",\"url\":\"https:\\/\\/111.111.111\",\"icon\":\"as fa-glob\"}]', '2025-06-14 20:02:28', '2025-06-14 20:02:28');

-- --------------------------------------------------------

--
-- 表的结构 `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `percentage` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `skills`
--

INSERT INTO `skills` (`id`, `title`, `description`, `percentage`, `icon`, `created_at`, `updated_at`) VALUES
(1, '前端开发', 'Python / Vue / JavaScript / HTML / CSS / PHP', 85, NULL, '2025-06-14 19:15:42', '2025-06-14 19:21:35'),
(3, '传统文化', '正骨 / 脉诊 / 针灸 / 相面 / 养生 / 减肥 / 玄学', 30, NULL, '2025-06-14 19:26:29', '2025-06-14 19:28:29'),
(4, '体育运动', '滑板 / 篮球 / 足球 / 羽毛球 / 骑行', 46, NULL, '2025-06-14 19:31:20', '2025-06-14 19:31:20'),
(5, '其他爱好', '机车 / 音乐制作 / 吉他 / 音乐节 / 美食', 30, NULL, '2025-06-14 19:32:01', '2025-06-14 19:32:01');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'fuzhen', '$2y$10$ZXRCPb8TDtEDEBkQ/3U1Cen95dYi5kfXj0C6ka7KAVoO6GI82piPq', '2025-06-14 17:43:30');

--
-- 转储表的索引
--

--
-- 表的索引 `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
