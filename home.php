<?php
session_start();
require_once 'db-connect.php';

if (isset($_POST['mail']) && isset($_POST['password'])) {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$mail]);
    $user = $sql->fetch();

    if ($user && password_verify($password, $user['user_pass'])) {
        $_SESSION['user'] = [
            'id' => $user['user_id'],
            'name' => $user['user_name'],
            'tel' => $user['tel']
        ];
        // 認証成功 → 続行
    } else {
        echo '<script>alert("メールアドレスまたはパスワードが正しくありません。"); window.location.href = "login.php";</script>';
        exit;
    }
}
?>

<?php


if (isset($_POST['keyword'])) {
    $_SESSION['keyword'] = $_POST['keyword'];
}

// セッションからキーワードを取得（なければ空文字）
$keyword = isset($_SESSION['keyword']) ? $_SESSION['keyword'] : '';
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
</head>
<body>
  <header>
        <div class="logo-container">
            <div class="logo"><img  src=img/SSBロゴ.png alt="SSBロゴ"></div>
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
            echo '<div>';
            echo '<img src="image/' . htmlspecialchars($item['food_id']) . '.png" alt="' . htmlspecialchars($item['food_name']) . '" width="100"><br>';
            echo '<strong>' . htmlspecialchars($item['food_name']) . '</strong><br>';
            echo htmlspecialchars($item['price']) . '円<br>';
            echo '</div>';
        }

      }
    }
        
        $sqlAll = $pdo->query('SELECT * FROM food_data');
        $allResults = $sqlAll->fetchAll();

        $flag=$pdo->query('SELECT * FROM food_data WHERE recommend_flag=1');
        $recommend_flag = $flag->fetchAll();

        echo '<h2>おすすめ商品</h2>';
        foreach ($recommend_flag as $flags) {
          echo '<img src="image/' . htmlspecialchars($flags['food_id']) . '.png" alt="' . htmlspecialchars($flags['food_name']) . '" width="100"><br>';
          echo htmlspecialchars($flags['food_name']) . '<br>' . htmlspecialchars($flags['price']) . '円<br>';
        }

        echo '<h2>全ての商品一覧</h2>';
        foreach ($allResults as $item) {
          echo '<img src="image/' . htmlspecialchars($item['food_id']) . '.png" alt="' . htmlspecialchars($item['food_name']) . '" width="100"><br>';
          echo htmlspecialchars($item['food_name']) . '<br>' . htmlspecialchars($item['price']) . '円<br>';
        }
  ?>



</body>
</html>