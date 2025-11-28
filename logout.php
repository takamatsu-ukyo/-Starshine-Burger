<?php
session_start();
$_SESSION = [];
session_destroy();

require 'db-connect.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト画面</title>
    <style>
        /* 全体のリセット */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Meiryo", sans-serif;
        }

        /* 背景 */
        body {
            min-height: 100vh;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* ぼかし背景（Blob）*/
        .bg-decor {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: -1;
        }

        .blob {
            position: absolute;
            width: 350px;
            height: 350px;
            background: #1976d2; /* 青 */
            filter: blur(100px);
            border-radius: 50%;
            top: -120px;
            left: -80px;
            animation: blobMove 12s ease-in-out infinite alternate;
        }

        .blob.b2 {
            background: #ff9800; /* オレンジ */
            bottom: -120px;
            right: -80px;
            width: 320px;
            height: 320px;
            animation-duration: 16s;
        }

        @keyframes blobMove {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 30px) scale(1.25); }
        }

        /* カード */
        main.card {
            max-width: 480px;
            width: 100%;
            background: #fff;
            padding: 35px 28px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        /* タイトル部分 */
        .brand {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            margin-bottom: 25px;
        }

        /* ロゴカラー → オレンジ */
        .logo {
            font-size: 48px;
            font-weight: 700;
            color: #ff9800;
        }

        /* ボタン（青基調） */
        button {
            padding: 12px 32px;
            font-size: 16px;
            background: #1976d2;  /* 青 */
            border: none;
            color: #fff;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1565c0;
            transform: translateY(-2px);
        }

        /* スマホ対応 */
        @media (max-width: 480px) {
            main.card {
                padding: 24px 16px;
            }
            .logo {
                font-size: 40px;
            }
        }
    </style>
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