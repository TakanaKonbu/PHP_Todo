<?php
// データベース接続
require_once 'db_connect.php';

// IDの取得
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?message=" . urlencode("タスクIDが指定されていません") . "&status=danger");
    exit();
}

$task_id = $_GET['id'];

// 削除前にタスク情報を取得（削除確認用）
try {
    $sql = "SELECT title FROM tasks WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$task) {
        header("Location: index.php?message=" . urlencode("タスクが見つかりませんでした") . "&status=warning");
        exit();
    }
    
    $task_title = $task['title'];
    
    // タスクを削除
    $sql = "DELETE FROM tasks WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $message = "タスク「" . htmlspecialchars($task_title) . "」を削除しました";
        $status = "success";
    } else {
        $message = "タスクの削除に失敗しました";
        $status = "danger";
    }
} catch(PDOException $e) {
    $message = "エラーが発生しました: " . $e->getMessage();
    $status = "danger";
}

// 一覧ページにリダイレクト
header("Location: index.php?message=" . urlencode($message) . "&status=" . $status);
exit();
?>
