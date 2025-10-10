<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
</head>
<body>
    <h2>SSB</h2>
    <h2>ログイン</h2>
    <form action="home.php" method="post">
    <p>メールアドレス<input type="email" name="mail" required pattern="^[a-zA-Z0-9._%+-]+@example\.com$"></p>
    <p>パスワード<input type="password" name="password"required minlength="8"></p>
    <button><a href="entry.php">新規登録</button>
    <input type="submit" value="ログイン">
    </form>
</body>
</html>