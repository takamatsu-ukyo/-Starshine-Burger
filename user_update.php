<?php
session_start();
require 'db-connect.php';

// ログインしていない場合はログイン画面へリダイレクト
if (!isset($_SESSION['user'])) {
    echo "<script>alert('ログインが必要です'); window.location.href='login.php';</script>";
    exit;
}

// 初期表示時はセッションから値を取得
$name = $_SESSION['user']['name'] ?? '';
$email = $_SESSION['user']['id'] ?? '';
$phone = $_SESSION['user']['tel'] ?? '';
$success = '';
$error = '';

// POST送信された場合のみ処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    try {
        // パスワードチェック
        if (!empty($password)) {
            if (strlen($password) < 8) {
                throw new Exception("パスワードは8文字以上で入力してください。");
            }
            if ($password !== $password_confirm) {
                throw new Exception("パスワードが確認用と一致しません。");
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET user_name = :name, tel = :phone, user_pass = :password WHERE user_id = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':password', $hashedPassword);
        } else {
            $sql = "UPDATE user SET user_name = :name, tel = :phone WHERE user_id = :email";
            $stmt = $pdo->prepare($sql);
        }

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        // セッション情報も更新
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['tel'] = $phone;

        $success = "更新が完了しました。";
    } catch (Exception $e) {
        $error = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    } catch (PDOException $e) {
        $error = "更新に失敗しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー情報更新</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<body>
  <div class="container mt-6">
    <h2 class="title has-text-centered" style="color: hsl(27, 82%, 51%)">SSB</h2>
    <h2 class="subtitle has-text-centered" style="color: hsl(27, 82%, 51%)">ユーザー情報更新</h2>
    <p class="has-text-centered mb-4">登録済みの情報を更新できます</p>

    <?php if (!empty($error)): ?>
      <div class="notification is-danger has-text-centered">
        <?= $error ?>
      </div>
    <?php elseif (!empty($success)): ?>
      <div class="notification is-success has-text-centered">
        <?= $success ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" class="box">
      <div class="field">
        <label class="label">名前</label>
        <div class="control">
          <input type="text" name="name" required class="input"
                value="<?= htmlspecialchars($name ?? '', ENT_QUOTES) ?>">
        </div>
      </div>

      <div class="field">
        <label class="label">メールアドレス（変更不可）</label>
        <div class="control">
          <input type="email" name="email" required class="input" value="<?= htmlspecialchars($email, ENT_QUOTES) ?>" readonly>
        </div>
      </div>

      <div class="field">
        <label class="label">電話番号</label>
        <div class="control">
          <input type="text" name="phone" required class="input"
                value="<?= htmlspecialchars($phone ?? '', ENT_QUOTES) ?>">
        </div>
      </div>

      <div class="field">
        <label class="label">パスワード</label>
        <div class="control">
          <input type="password" name="password" required class="input" minlength="8">
        </div>
        <p class="help">8文字以上で入力してください</p>
      </div>

      <div class="field">
        <label class="label">パスワード再入力</label>
        <div class="control">
          <input type="password" name="password_confirm" required class="input" minlength="8">
        </div>
      </div>

      <div class="field is-grouped is-grouped-centered">
        <p class="control">
          <input type="submit" value="更新する" class="button is-primary">
        </p>
        <p class="control">
          <a href="home.php" class="button is-light">ホームへ戻る</a>
        </p>
      </div>
    </form>
  </div>
</body>
</html>