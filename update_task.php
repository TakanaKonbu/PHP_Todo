<?php
// データベース接続
require_once 'db_connect.php';

// POSTデータのチェック
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POSTデータを取得
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $priority = isset($_POST['priority']) ? $_POST['priority'] : 2;
    $status = isset($_POST['status']) ? $_POST['status'] : 0;
    
    try {
        // SQL文の準備
        $sql = "UPDATE tasks 
                SET title = :title, description = :description, due_date = :due_date, 
                    priority = :priority, status = :status
                WHERE id = :id";
        
        // クエリの準備
        $stmt = $conn->prepare($sql);
        
        // パラメータを紐付け
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':priority', $priority, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        
        // クエリの実行
        $stmt->execute();
        
        // 成功メッセージの設定
        $message = "タスクが正常に更新されました";
        $status = "success";
    } catch(PDOException $e) {
        // エラーメッセージの設定
        $message = "エラー: " . $e->getMessage();
        $status = "danger";
    }
    
    // リダイレクト
    header("Location: view_task.php?id=" . $id . "&message=" . urlencode($message) . "&status=" . $status);
    exit();
}

// POSTでない場合は一覧ページにリダイレクト
header("Location: index.php");
exit();
?>
