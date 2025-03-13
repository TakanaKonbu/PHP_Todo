<?php
// データベース接続情報
$servername = "localhost";
$username = "root";  // MAMPのデフォルトユーザー名（環境によって変更が必要）
$password = "root";  // MAMPのデフォルトパスワード（環境によって変更が必要）
$dbname = "todo_app";  // 先ほど作成したデータベース名

// データベースに接続
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // PDOのエラーモードを例外に設定
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "データベース接続成功"; // デバッグ用
} catch(PDOException $e) {
    echo "接続失敗: " . $e->getMessage();
    die();
}
?>
