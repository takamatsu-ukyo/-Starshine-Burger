<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<body>
  <div class="container mt-6">
    <h2 class="title has-text-centered" style="color: hsl(27, 82%, 51%)">SSB</h2>
    <h2 class="subtitle has-text-centered" style="color: hsl(27, 82%, 51%)">ログイン</h2>

    <form action="home.php" method="post" class="box">
      <div class="field">
        <label class="label">メールアドレス</label>
        <div class="control">
          <input type="email" name="mail" required pattern="^[a-zA-Z0-9._%+-]+@example\.com$" class="input">
        </div>
      </div>

      <div class="field">
        <label class="label">パスワード</label>
        <div class="control">
          <input type="password" name="password" required minlength="8" class="input">
        </div>
      </div>

      <div class="field is-grouped is-grouped-centered">
        <p class="control">
          <a href="entry.php" class="button is-link is-light">新規登録</a>
        </p>
        <p class="control">
          <input type="submit" value="ログイン" class="button is-primary">
        </p>
      </div>
    </form>
    <div class="has-text-centered">
      <img src = "img/SSBロゴ.png" alt = "SSBロゴ" style="display: block; margin: 20px auto 0; max-width: 200px;">
    </div>
  </div>
</body>
</html>