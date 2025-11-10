<?php require 'db-connect.php';?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
</head>
<body>
    <h1 id="kanriloginTitle">ログイン</h1>

    <form action="#" method="post" onsubmit="event.preventDefault(); alert('送信処理は実装されていません');">

     <div class="field">
        <label for="id">ユーザー名</label>
        <input id="id" name="id" type="text">
      </div>

      <div class="field">
        <label for="pass">パスワード</label>
        <input id="pass" name="pass" type="password" autocomplete="current-password" />
    </div>

      <button type="submit">ログイン</button>
    </form>
</body>
</html>