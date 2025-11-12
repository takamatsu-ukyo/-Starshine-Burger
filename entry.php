<?php
require 'db-connect.php';

// POST送信された場合のみ処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    // パスワードをハッシュ化
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO user (user_id, user_pass, user_name, tel) VALUES (:email, :password, :name, :phone)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // 登録成功 → ログイン画面へリダイレクト
        header("Location: login.php");
        exit;

    } catch (PDOException $e) {
        $error = "登録に失敗しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録画面</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<body>
  <div class="container mt-6">
    <h2 class="title has-text-centered has-text-warning">SSB</h2>
    <h2 class="subtitle has-text-centered has-text-warning">新規登録</h2>

    <?php if (!empty($error)): ?>
      <div class="notification is-danger has-text-centered">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" class="box">
      <div class="field">
        <label class="label">名前</label>
        <div class="control">
          <input type="text" name="name" required class="input">
        </div>
      </div>

      <div class="field">
        <label class="label">メールアドレス</label>
        <div class="control">
          <input type="email" name="email" required class="input">
        </div>
      </div>

      <div class="field">
        <label class="label">電話番号</label>
        <div class="control">
          <input type="text" name="phone" required class="input">
        </div>
      </div>

      <div class="field">
        <label class="label">パスワード</label>
        <div class="control">
          <input type="password" name="password" required class="input">
        </div>
      </div>

      <div class="field is-grouped is-grouped-centered">
        <p class="control">
          <input type="submit" value="登録完了" class="button is-primary">
        </p>
      </div>
    </form>
  </div>
</body>
</html>