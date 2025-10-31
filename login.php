<?php require 'db-connect.php';?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<body>
    <div>
        <h2 class="has-text-centered hsl(27, 82%, 51%)">SSB</h2>
        <h2 class="has-text-centered hsl(27, 82%, 51%)">ログイン</h2>
        <form action="home.php" method="post">
        <p>メールアドレス</p>
        <p><input type="email" name="mail" required pattern="^[a-zA-Z0-9._%+-]+@example\.com$" class="table-cell-border-color: #E5C287;"></p>
        <p>パスワード</p>
        <p><input type="password" name="password"required minlength="8" class="table-cell-border-color: #E5C287;></p>
        <button><a href="entry.php" class="button is-danger" style="background-color: rgb(232, 117, 26); color: rgb(0, 0, 0);">新規登録</button>
        <input type="submit" value="ログイン" class="button is-danger" style="background-color: rgb(232, 117, 26); color: rgb(0, 0, 0);">
        </form>
    </div>
</body>
</html>