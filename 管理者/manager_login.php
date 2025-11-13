<?php require 'db-connect.php';?>
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
  </style>
</head>
<body>
  <div class="login-container">
    <h1>ログイン</h1>

    <form action="#" method="post" onsubmit="event.preventDefault(); alert('送信処理は未実装です');">
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
