<?php
require 'db-connect.php';

// POST送信された場合のみ処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    try {
        // パスワードチェック
        if (strlen($password) < 8) {
            throw new Exception("パスワードは8文字以上で入力してください。");
        }
        if ($password !== $password_confirm) {
            throw new Exception("パスワードが確認用と一致しません。");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (user_id, user_pass, user_name, tel)
                VALUES (:email, :password, :name, :phone)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        header("Location: login.php");
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() === '23000' && isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
            $error = "既に登録されているメールアドレスです。";
        } else {
            $error = "登録に失敗しました。管理者にお問い合わせください。";
            // error_log($e->getMessage());
        }
    } catch (Exception $e) {
        $error = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
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
    <h2 class="title has-text-centered" style="color: hsl(27, 82%, 51%)">SSB</h2>
    <h2 class="subtitle has-text-centered" style="color: hsl(27, 82%, 51%)">新規登録</h2>

    <?php if (!empty($error)): ?>
      <div class="notification is-danger has-text-centered">
        <?= $error ?>
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
        <label class="label">メールアドレス</label>
        <div class="control">
          <input type="email" name="email" required class="input"
                value="<?= htmlspecialchars($email ?? '', ENT_QUOTES) ?>">
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
          <input type="submit" value="登録完了" class="button is-primary">
        </p>
      </div>
    </form>
  </div>
</body>
</html>