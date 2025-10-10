<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト画面</title>
</head>
<body>
    <div class="bg-decor" aria-hidden="true">
        <div class="blob"></div>
        <div class="blob b2"></div>
    </div>

    <main class="card" role="main" aria-labelledby="logoutTitle">
        <div class="brand">
            <div class="logo" aria-hidden="true">SSB</div>
            <div>
                <h1 id="logoutTitle">ログアウトしました</h1>
                <p>ご利用ありがとうございました。</p>
            </div>
        </div>
        <section class="field" style="text-align:center;">
            <p>再度ログインする場合は以下のボタンを押してください。</p>
            <a href="login.php">
                <button type="button">ログイン画面に戻る</button>
            </a>
        </section>
    </main>
</body>
</html>