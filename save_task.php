<?php
// データベース接続
require_once 'db_connect.php';

// POSTデータのチェック
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POSTデータを取得
    $title = $_POST['title'];
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $priority = isset($_POST['priority']) ? $_POST['priority'] : 2;
    
    try {
        // SQL文の準備
        $sql = "INSERT INTO tasks (title, description, due_date, priority) 
                VALUES (:title, :description, :due_date, :priority)";
        
        // クエリの準備
        $stmt = $conn->prepare($sql);
        
        // パラメータを紐付け
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':priority', $priority, PDO::PARAM_INT);
        
        // クエリの実行
        $stmt->execute();
        
        // 成功メッセージの設定
        $message = "タスクが正常に追加されました";
        $status = "success";
    } catch(PDOException $e) {
        // エラーメッセージの設定
        $message = "エラー: " . $e->getMessage();
        $status = "danger";
    }
    
    // 一時的なメッセージを含めてリダイレクト（今後はセッションを使った方法に改良予定）
    header("Location: add_task.php?message=" . urlencode($message) . "&status=" . $status);
    exit();
}
?>