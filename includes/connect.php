<?php
if (!defined('_CODE')) {
    die("Access denied");
}

try {
    if (class_exists('PDO')) {
        $dsn = 'mysql:dbname=' . _DB . ';host=' . _HOST;
        $option = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', // set utf8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Tạo thông báo ra ngoại lệ khi gặp lỗi
        ];

        $conn = new PDO($dsn, _USER, _PASS, $option);
    }
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . '<br>';
    echo "File : " . $e->getFile() . '<br>';
    echo "Line : " . $e->getLine();
}
