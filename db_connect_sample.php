<?php
$dsn = 'mysql:host=xxxx;dbname=xxxx;charset=utf8mb4';
$user = 'xxxx';
$password = 'xxxx';
// 公開用は情報を伏せています。
// 【MySQL】接続先。【host=】データベースホスト名。【dbname=】データベース名。【charset=utf8mb4】文字コードをUTF-8に。

try {
    $pdo = new PDO($dsn, $user, $password); // MySQL と PHP をつなぐための関数
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // SQLにミスがあった時、例外（Exception）となる
} catch (PDOException $e) {
    echo 'DB接続エラー: ' . $e->getMessage(); // 原因もあわせてエラーメッセージが出る
    exit;
}
?>
