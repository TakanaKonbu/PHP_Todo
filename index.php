<?php
// データベース接続
require_once 'db_connect.php';

// タイトルの設定
$pageTitle = "タスク一覧";

// タスクの取得（未完了のみ、優先度順）
try {
    $sql = "SELECT * FROM tasks WHERE status = 0 ORDER BY priority ASC, due_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "エラー: " . $e->getMessage();
    $tasks = [];
}

// 完了タスクの取得
try {
    $sql = "SELECT * FROM tasks WHERE status = 1 ORDER BY updated_at DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $completedTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "エラー: " . $e->getMessage();
    $completedTasks = [];
}

// 優先度を表示用のテキストに変換する関数
function getPriorityText($priority) {
    switch ($priority) {
        case 1:
            return '<span class="badge bg-danger">高</span>';
        case 2:
            return '<span class="badge bg-warning text-dark">中</span>';
        case 3:
            return '<span class="badge bg-info text-dark">低</span>';
        default:
            return '<span class="badge bg-secondary">不明</span>';
    }
}

// 期限日の表示形式を整える関数
function formatDueDate($date) {
    if (empty($date)) {
        return '未設定';
    }
    $dateObj = new DateTime($date);
    $today = new DateTime('today');
    $diff = $today->diff($dateObj);
    
    // 表示形式
    $formattedDate = $dateObj->format('Y年m月d日');
    
    // 期限切れの場合
    if ($dateObj < $today) {
        return '<span class="text-danger">' . $formattedDate . ' (期限切れ)</span>';
    }
    
    // 今日が期限の場合
    if ($diff->days == 0) {
        return '<span class="text-warning">' . $formattedDate . ' (今日)</span>';
    }
    
    // 明日が期限の場合
    if ($diff->days == 1) {
        return '<span class="text-warning">' . $formattedDate . ' (明日)</span>';
    }
    
    return $formattedDate;
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
        
        <div class="mb-3 text-end">
            <a href="add_task.php" class="btn btn-primary">+ 新しいタスクを追加</a>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5>未完了タスク</h5>
            </div>
            <div class="card-body">
                <?php if (count($tasks) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>タイトル</th>
                                <th>優先度</th>
                                <th>期限日</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                <td><?php echo getPriorityText($task['priority']); ?></td>
                                <td><?php echo formatDueDate($task['due_date']); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="view_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-primary">詳細</a>
                                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-secondary">編集</a>
                                        <a href="complete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('このタスクを完了としてマークしますか？')">完了</a>
                                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('このタスクを削除してもよろしいですか？')">削除</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-center">未完了のタスクはありません。</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>最近完了したタスク（最新5件）</h5>
            </div>
            <div class="card-body">
                <?php if (count($completedTasks) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>タイトル</th>
                                <th>完了日時</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($completedTasks as $task): ?>
                            <tr>
                                <td><s><?php echo htmlspecialchars($task['title']); ?></s></td>
                                <td><?php echo (new DateTime($task['updated_at']))->format('Y/m/d H:i'); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="view_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-primary">詳細</a>
                                        <a href="undo_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-warning">元に戻す</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-center">完了したタスクはありません。</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>