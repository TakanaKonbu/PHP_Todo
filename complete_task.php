<?php
// データベース接続
require_once 'db_connect.php';

// IDの取得
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?message=" . urlencode("タスクIDが指定されていません") . "&status=danger");
    exit();
}

$task_id = $_GET['id'];

try {
    // タスクのステータスを「完了」(1)に更新
    $sql = "UPDATE tasks SET status = 1 WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $message = "タスクを完了しました";
        $status = "success";
    } else {
        $message = "該当するタスクが見つかりませんでした";
        $status = "warning";
    }
} catch(PDOException $e) {
    $message = "エラーが発生しました: " . $e->getMessage();
    $status = "danger";
}

// 一覧ページにリダイレクト
header("Location: index.php?message=" . urlencode($message) . "&status=" . $status);
exit();
?>
