<?php
session_start();
if (isset($_POST['keyword'])) {
    $_SESSION['keyword'] = $_POST['keyword'];
}

// セッションからキーワードを取得（なければ空文字）
$keyword = isset($_SESSION['keyword']) ? $_SESSION['keyword'] : '';

// メニュー開閉の状態をセッションで管理
if (isset($_GET['menu'])) {
    $_SESSION['menu_open'] = ($_GET['menu'] === 'open');
}
$menu_open = isset($_SESSION['menu_open']) && $_SESSION['menu_open'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム画面</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .main-container {
            width: 100%;
            max-width: 500px;
            background-color: white;
            min-height: 100vh;
            position: relative;
        }

        /* ヘッダー */
        header {
            background-color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
            width: 100%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 32px;
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
            min-height: 100px;
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

        /* 検索バー */
        .search-container {
            padding: 20px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .search-box {
            display: flex;
            gap: 10px;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .search-box input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-button {
            padding: 12px 30px;
            background-color: #ff8c00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #e67e00;
        }

        /* フィルターボタン */
        .filter-container {
            padding: 0 20px 20px;
            width: 100%;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-button {
            padding: 10px 20px;
            background-color: #ffc107;
            color: #333;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
        }

        /* カート */
        .cart-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .cart-icon img {
            width: 30px;
            height: 30px;
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
    echo '<div class="search-container">
        <div class="search-box">';
    echo '<form method="POST" action="">';
    echo '<input type="text" name="keyword" id="searchInput" value="',htmlspecialchars($keyword),'" placeholder="商品名を入力">';
    echo '<button type="submit"  class="search-button">検索</button>';
    echo '<div class="cart-icon"><a href="cart.php"><img src="img/カートのアイコン素材.png" alt="カートアイコン"></a></div>';
    echo '</form>';
    echo '</div>
          </div>';
    ?>

    <div class="filter-container">
    <p>タグから選ぶ：</p>
    <form method="POST" action="" id="tagForm">
        <input type="hidden" name="keyword" id="tagKeyword">
        <button type="button" class="filter-button" onclick="submitTag('')">オススメ</button>
        <button type="button" class="filter-button" onclick="submitTag('チーズ')">チーズ</button>
        <button type="button" class="filter-button" onclick="submitTag('てりやき')">てりやき</button>
        <button type="button" class="filter-button" onclick="submitTag('ポテト')">ポテト</button>
    </form>
    </div>

  <script>
    function submitTag(tag) {
      document.getElementById('tagKeyword').value = tag;
      document.getElementById('tagForm').submit();
    }
  </script>

  <?php
    require_once 'db-connect.php';

    if ($keyword) {
    
    $sql = $pdo->prepare('SELECT * FROM food_data WHERE food_name LIKE ?');
    $sql->execute(['%' . $keyword . '%']);
    $results = $sql->fetchAll();
    if (empty($results)) {
        echo '<p>該当する商品は見つかりませんでした。</p>';
    } else{
        echo '<h2>関連する商品</h2>';
        foreach ($results as $item) {
          echo '<form method="post" action="cart.php" target="hiddenFrame">';
          echo '<input type="hidden" name="food_id" value="' . htmlspecialchars($item['food_id']) . '">';
          echo '<input type="hidden" name="food_name" value="' . htmlspecialchars($item['food_name']) . '">';
          echo '<input type="hidden" name="price" value="' . htmlspecialchars($item['price']) . '">';
          echo '<button onclick="alert(\'カートに ',htmlspecialchars($item['food_name']),' を追加しました！\')" type="submit">';
          echo '<img src="image/' . htmlspecialchars($item['food_id']) . '.png" alt="' . htmlspecialchars($item['food_name']) . '" width="100"><br>';
          echo htmlspecialchars($item['food_name']) . '<br>' . htmlspecialchars($item['price']) . '円<br>';
          echo '</button>';
          echo '</form>';
        }

      }
    }
        
        $sqlAll = $pdo->query('SELECT * FROM food_data');
        $allResults = $sqlAll->fetchAll();

        $flag=$pdo->query('SELECT * FROM food_data WHERE recommend_flag=1');
        $recommend_flag = $flag->fetchAll();

        echo '<h2>おすすめ商品</h2>';
        foreach ($recommend_flag as $flags) {
          echo '<form method="post" action="cart.php" target="hiddenFrame">';
          echo '<input type="hidden" name="food_id" value="' . htmlspecialchars($flags['food_id']) . '">';
          echo '<input type="hidden" name="food_name" value="' . htmlspecialchars($flags['food_name']) . '">';
          echo '<input type="hidden" name="price" value="' . htmlspecialchars($flags['price']) . '">';
          echo '<button onclick="alert(\'カートに ',htmlspecialchars($flags['food_name']),' を追加しました！\')" type="submit">';
          echo '<img src="image/' . htmlspecialchars($flags['food_id']) . '.png" alt="' . htmlspecialchars($flags['food_name']) . '" width="100"><br>';
          echo htmlspecialchars($flags['food_name']) . '<br>' . htmlspecialchars($flags['price']) . '円<br>';
          echo '</button>';
          echo '</form>';
        }

        echo '<h2>全ての商品一覧</h2>';
        foreach ($allResults as $item) {
          echo '<form method="post" action="cart.php" target="hiddenFrame">';
          echo '<input type="hidden" name="food_id" value="' . htmlspecialchars($item['food_id']) . '">';
          echo '<input type="hidden" name="food_name" value="' . htmlspecialchars($item['food_name']) . '">';
          echo '<input type="hidden" name="price" value="' . htmlspecialchars($item['price']) . '">';
          echo '<button onclick="alert(\'カートに ',htmlspecialchars($item['food_name']),' を追加しました！\')" type="submit">';
          echo '<img src="image/' . htmlspecialchars($item['food_id']) . '.png" alt="' . htmlspecialchars($item['food_name']) . '" width="100"><br>';
          echo htmlspecialchars($item['food_name']) . '<br>' . htmlspecialchars($item['price']) . '円<br>';
          echo '</button>';
          echo '</form>';
        }
  ?>


   <script>
        const menuButton = document.getElementById('menuButton');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const overlay = document.getElementById('overlay');
        const searchInput = document.getElementById('searchInput');

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

        // タグボタンで検索バーに単語を入力
        function setSearchTag(tag) {
            searchInput.value = tag;
            searchInput.focus();
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