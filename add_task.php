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
            } catch (PDOException $e) {
                // エラーメッセージの設定
                $message = "エラー: " . $e->getMessage();
                $status = "danger";
            }

            // 一時的なメッセージを含めてリダイレクト（今後はセッションを使った方法に改良予定）
            header("Location: add_task.php?message=" . urlencode($message) . "&status=" . $status);
            exit();
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