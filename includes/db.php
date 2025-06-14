<?php
// 数据库配置
$host = 'localhost';
$port = "3306";
$dbname = 'home1';
$username = 'home1';
$password = 'mBL8iRSTkfNMaxEY';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("数据库连接失败: " . $e->getMessage());
}
?>