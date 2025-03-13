<?php
// データベース接続
require_once 'db_connect.php';

// タイトルの設定
$pageTitle = "タスク詳細";

// IDの取得
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?message=" . urlencode("タスクIDが指定されていません") . "&status=danger");
    exit();
}

$task_id = $_GET['id'];

// タスクの取得
try {
    $sql = "SELECT * FROM tasks WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$task) {
        header("Location: index.php?message=" . urlencode("タスクが見つかりませんでした") . "&status=warning");
        exit();
    }
} catch(PDOException $e) {
    header("Location: index.php?message=" . urlencode("エラー: " . $e->getMessage()) . "&status=danger");
    exit();
}

// 優先度のテキスト取得
function getPriorityText($priority) {
    switch ($priority) {
        case 1:
            return '高';
        case 2:
            return '中';
        case 3:
            return '低';
        default:
            return '不明';
    }
}

// ステータスのテキスト取得
function getStatusText($status) {
    return $status == 1 ? '完了' : '未完了';
}

// ステータスに基づいたバッジのクラス
function getStatusBadgeClass($status) {
    return $status == 1 ? 'bg-success' : 'bg-warning text-dark';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | ToDoアプリ</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4"><?php echo $pageTitle; ?></h1>
        
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo htmlspecialchars($task['title']); ?></h5>
                    <span class="badge <?php echo getStatusBadgeClass($task['status']); ?>">
                        <?php echo getStatusText($task['status']); ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>優先度：</strong>
                        <?php echo getPriorityText($task['priority']); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>期限日：</strong>
                        <?php echo !empty($task['due_date']) ? date('Y年m月d日', strtotime($task['due_date'])) : '未設定'; ?>
                    </div>
                    <div class="col-md-4">
                        <strong>作成日：</strong>
                        <?php echo date('Y年m月d日 H:i', strtotime($task['created_at'])); ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>詳細：</strong>
                    <?php if (!empty($task['description'])): ?>
                    <div class="card">
                        <div class="card-body bg-light">
                            <?php echo nl2br(htmlspecialchars($task['description'])); ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">詳細はありません</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="index.php" class="btn btn-secondary">戻る</a>
                    </div>
                    <div>
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-primary">編集</a>
                        <?php if ($task['status'] == 0): ?>
                        <a href="complete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-success" onclick="return confirm('このタスクを完了としてマークしますか？')">完了</a>
                        <?php else: ?>
                        <a href="undo_task.php?id=<?php echo $task['id']; ?>" class="btn btn-warning">元に戻す</a>
                        <?php endif; ?>
                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-danger" onclick="return confirm('このタスクを削除してもよろしいですか？')">削除</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
