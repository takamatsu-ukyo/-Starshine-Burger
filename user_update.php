<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー情報更新</title>
    <P>登録済みの情報を更新できます</p>
</head>
<body>
     <form action="#" method="post">
      <div class="field">
        <label for="name">ユーザー名</label>
        <input id="name" name="name" type="text" value="仮名前">
      </div>

      <div class="field">
        <label for="email">メールアドレス</label>
        <input id="email" name="email" type="email" value="仮メール@example.com">
      </div>

      <div class="field">
        <label for="pass">電話番号</label>
        <input id="pass" name="TEL" type="tel" placeholder="電話番号の入力">
      </div>

      <div class="field">
        <label for="pass">新しいパスワード</label>
        <input id="pass" name="password" type="password" placeholder="変更する場合のみ入力">
      </div>

      <button type="submit">更新する</button>

      <div class="links">
        <a href="#">キャンセル</a>
      </div>
    </form>
  </main>
</body>
</html>