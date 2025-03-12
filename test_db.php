<?php
// データベース接続ファイルを読み込む
require_once 'db_connect.php';

// 接続確認
echo "データベース接続テスト:<br>";
try {
    // バージョン情報を取得して表示
    echo "MySQL バージョン: " . $conn->getAttribute(PDO::ATTR_SERVER_VERSION) . "<br>";
    echo "接続成功しました！";
} catch(PDOException $e) {
    echo "接続エラー: " . $e->getMessage();
}
?>