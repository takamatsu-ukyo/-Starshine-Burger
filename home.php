<?php session_start(); ?>
<?php require 'db-connect.php';?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム画面</title>
    <style>
      header {
            background-color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 200px;
            height: 200px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 100px;
            font-weight: bold;
            color: #ff8c00;
        }

        /* ハンバーガーメニューボタン */
        .menu-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            z-index: 1001;
        }

        .menu-icon {
            width: 30px;
            height: 3px;
            background-color: #333;
            display: block;
            position: relative;
            transition: all 0.3s ease;
        }

        .menu-icon::before,
        .menu-icon::after {
            content: '';
            width: 30px;
            height: 3px;
            background-color: #333;
            position: absolute;
            left: 0;
            transition: all 0.3s ease;
        }

        .menu-icon::before {
            top: -10px;
        }

        .menu-icon::after {
            top: 10px;
        }

        /* メニューが開いた時のアニメーション */
        .menu-button.active .menu-icon {
            background-color: transparent;
        }

        .menu-button.active .menu-icon::before {
            transform: rotate(45deg);
            top: 0;
        }

        .menu-button.active .menu-icon::after {
            transform: rotate(-45deg);
            top: 0;
        }

        /* ドロップダウンメニュー */
        .dropdown-menu {
            position: absolute;
            top: 70px;
            right: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            display: none;
            z-index: 1000;
            min-width: 200px;
        }

        .dropdown-menu.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-menu ul {
            list-style: none;
        }

        .dropdown-menu li {
            border-bottom: 1px solid #eee;
        }

        .dropdown-menu li:last-child {
            border-bottom: none;
        }

        .dropdown-menu a {
            display: block;
            padding: 15px 20px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }
        </style>
</head>
<body>
  <header>
        <div class="logo-container">
            <div class="logo"><img  src=img/SSBロゴ.png alt="SSBロゴ" class="logo"></div>
            <div class="logo-text">SSB</div>
        </div>
        
        <button class="menu-button" id="menuButton">
            <span class="menu-icon"></span>
        </button>

        <!-- ドロップダウンメニュー -->
        <div class="dropdown-menu" id="dropdownMenu">
            <ul>
                <li><a href="#" onclick="handleMenuClick('ユーザー情報')">・ユーザー情報</a></li>
                <li><a href="#" onclick="handleMenuClick('ログアウト')">・ログアウト</a></li>
            </ul>
        </div>
    </header>
    <?php
    unset($_SESSION['keyword'])
    echo '<h2>SSB</h2>';
    echo '<form method="POST" action="">';
    echo '<input type="text" name="keyword" value="',htmlspecialchars($keyword),'" placeholder="商品名を入力">';
    echo '<button type="submit">検索</button>';
    echo '</form>';
    // キーワード取得（POST）
    $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
    ?>
    <p>タグから選ぶ：</p>
    <form method="POST" action="" id="tagForm">
        <input type="hidden" name="keyword" id="tagKeyword">
        <button type="button" onclick="submitTag('チーズ')">チーズ</button>
        <button type="button" onclick="submitTag('てりやき')">てりやき</button>
        <button type="button" onclick="submitTag('ポテト')">ポテト</button>
    </form>

  <script>
    function submitTag(tag) {
      document.getElementById('tagKeyword').value = tag;
      document.getElementById('tagForm').submit();
    }
  </script>

  <!-- 検索結果（例） -->
  <?php if ($keyword): ?>
    <h2>「<?= htmlspecialchars($keyword) ?>」の検索結果</h2>
    <!-- 商品検索処理をここに追加 -->
  <?php endif; ?>

   <script>
        const menuButton = document.getElementById('menuButton');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const overlay = document.getElementById('overlay');

        // メニューボタンのクリックイベント
        menuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            menuButton.classList.toggle('active');
            dropdownMenu.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        // オーバーレイクリックでメニューを閉じる
        overlay.addEventListener('click', function() {
            menuButton.classList.remove('active');
            dropdownMenu.classList.remove('show');
            overlay.classList.remove('show');
        });

        // メニュー項目のクリック処理
        function handleMenuClick(action) {
            alert(action + 'が選択されました');
            menuButton.classList.remove('active');
            dropdownMenu.classList.remove('show');
            overlay.classList.remove('show');
        }

        // ドキュメント全体のクリックでメニューを閉じる
        document.addEventListener('click', function(e) {
            if (!menuButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                menuButton.classList.remove('active');
                dropdownMenu.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    </script>
</body>
</html>