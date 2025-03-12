<?php
// タイトルの設定
$pageTitle = "タスク追加";
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

        <form action="save_task.php" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">タイトル</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">詳細</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">期限日</label>
                <input type="date" class="form-control" id="due_date" name="due_date">
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">優先度</label>
                <select class="form-select" id="priority" name="priority">
                    <option value="1">高</option>
                    <option value="2" selected>中</option>
                    <option value="3">低</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">保存</button>
            <a href="index.php" class="btn btn-secondary">戻る</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>