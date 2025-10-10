<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録画面</title>
</head>
<body>
  <h1>登録</h1>
  <form action="register.php" method="POST">
    <label>名前: <input type="text" name="name" required></label><br>
    <label>メールアドレス: <input type="email" name="email" required></label><br>
    <label>電話番号: <input type="text" name="phone" required></label><br>
    <label>パスワード: <input type="password" name="password" required></label><br>
    <button type="submit">登録完了</button>
  </form>
</body>
</html>