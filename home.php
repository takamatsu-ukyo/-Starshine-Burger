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
            width: 60px;
            height: auto;
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
    </style>
</head>
<body>
  <iframe name="hiddenFrame" style="display:none;"></iframe>
  <header>
        <div class="logo-container">
            <div class="logo"><img  src=img/SSBロゴ.png alt="SSBロゴ"></div>
            <div class="logo-text">SSB</div>
        </div>
        
        <a href="?menu=<?= $menu_open ? 'close' : 'open' ?>" class="menu-button <?= $menu_open ? 'active' : '' ?>" id="menuButton">
        <span class="menu-icon"></span>
        </a>

    </header>
    <?php if ($menu_open): ?>
        <div class="dropdown-menu show" id="dropdownMenu">
            <div class="menu">
            <a href="user.php">ユーザー情報</a>
            <a href="logout.php">ログアウト</a>
            </div>
        </div>
<?php endif; ?>

    <?php
    echo '<h2>SSB</h2>';
    echo '<form method="POST" action="">';
    echo '<input type="text" name="keyword" value="',htmlspecialchars($keyword),'" placeholder="商品名を入力">';
    echo '<button type="submit">検索</button>';
    echo '<a href="cart.php">カートへ</a>';
    echo '</form>';
    ?>
    <p>タグから選ぶ：</p>
    <form method="POST" action="" id="tagForm">
        <input type="hidden" name="keyword" id="tagKeyword">
        <button type="button" onclick="submitTag('')">オススメ</button>
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



</body>
</html>