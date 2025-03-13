<?php
// データベース接続
require_once 'db_connect.php';

// タイトルの設定
$pageTitle = "タスク編集";

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
        
        <?php
        // メッセージの表示
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            $status = isset($_GET['status']) ? $_GET['status'] : 'info';
            echo '<div class="alert alert-' . $status . ' alert-dismissible fade show" role="alert">';
            echo $message;
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>';
            echo '</div>';
        }
        ?>
        
        <form action="update_task.php" method="post">
            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
            
            <div class="mb-3">
                <label for="title" class="form-label">タイトル</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">詳細</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="due_date" class="form-label">期限日</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo $task['due_date']; ?>">
            </div>
            
            <div class="mb-3">
                <label for="priority" class="form-label">優先度</label>
                <select class="form-select" id="priority" name="priority">
                    <option value="1" <?php echo $task['priority'] == 1 ? 'selected' : ''; ?>>高</option>
                    <option value="2" <?php echo $task['priority'] == 2 ? 'selected' : ''; ?>>中</option>
                    <option value="3" <?php echo $task['priority'] == 3 ? 'selected' : ''; ?>>低</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">ステータス</label>
                <select class="form-select" id="status" name="status">
                    <option value="0" <?php echo $task['status'] == 0 ? 'selected' : ''; ?>>未完了</option>
                    <option value="1" <?php echo $task['status'] == 1 ? 'selected' : ''; ?>>完了</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">更新</button>
            <a href="view_task.php?id=<?php echo $task['id']; ?>" class="btn btn-secondary">キャンセル</a>
        </form>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>