<?php
require 'db-connect.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? '';
    $pass = $_POST['pass'] ?? '';

    if ($id === "" || $pass === "") {
        $error = "IDまたはパスワードを入力してください。";
    } else {
        $sql = "SELECT * FROM manager_data WHERE id = :id AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':password', $pass, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {
            // ログイン成功 → manager_home.phpへ
            $_SESSION['login'] = true;
            $_SESSION['manager_id'] = $id; // 必要なら管理者IDを保持
            header("Location: manager_home.php");
            exit;
        } else {
            $error = "IDまたはパスワードが正しくありません。";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理者ログイン</title>
  <style>
    body {
      font-family: "Meiryo", sans-serif;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      background-color: #fff;
      width: 320px;
      padding: 40px 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    h1 {
      color: #e87722; /* オレンジ */
      margin-bottom: 30px;
      font-size: 1.6em;
      font-weight: bold;
    }
    .field {
      text-align: left;
      margin-bottom: 20px;
    }
    label {
      display: block;
      font-size: 0.9em;
      color: #333;
      margin-bottom: 6px;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #f2c38b;
      border-radius: 4px;
      font-size: 1em;
      box-sizing: border-box;
      outline: none;
    }
    input:focus {
      border-color: #e87722;
      box-shadow: 0 0 3px #f5b37d;
    }
    button {
      width: 100%;
      background-color: #e87722;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 10px;
      font-size: 1em;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #d5691f;
    }
    .error {
      color: red;
      margin-bottom: 15px;
      font-size: 0.9em;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h1>ログイン</h1>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="" method="post">
      <div class="field">
        <label for="id">ID</label>
        <input id="id" name="id" type="text" placeholder="Enter ID">
      </div>

      <div class="field">
        <label for="pass">パスワード</label>
        <input id="pass" name="pass" type="password" placeholder="Enter Password" autocomplete="current-password">
      </div>

      <button type="submit">ログイン</button>
    </form>
  </div>
</body>
</html>