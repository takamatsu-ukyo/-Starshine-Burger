<?php
session_start();
require 'db-connect.php';

// リロード（GETアクセス）時は検索キーワードをクリア
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['keyword']);
    $keyword = '';
} else {
    // POSTされたらキーワードを保存
    if (isset($_POST['keyword'])) {
        $_SESSION['keyword'] = $_POST['keyword'];
    }
    $keyword = isset($_SESSION['keyword']) ? $_SESSION['keyword'] : '';
}

$pdo = new PDO($connect, USER, PASS);

// おすすめ商品
$recommend_sql = $pdo->query('SELECT * FROM food_data WHERE recommend_flag = 1');
$recommend_items = $recommend_sql->fetchAll();

// 全商品
$all_sql = $pdo->query('SELECT * FROM food_data');
$all_items = $all_sql->fetchAll();

// 検索処理
$search_results = [];
$searched = isset($_POST['keyword']);
if ($searched) {
    $search_sql = $pdo->prepare('SELECT * FROM food_data WHERE food_name LIKE ?');
    $search_sql->execute(['%' . $keyword . '%']);
    $search_results = $search_sql->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム画面</title>
</head>
<body>
<<<<<<< Updated upstream
    <?php
    echo '<h2>SSB</h2>';
    echo '<form method="POST" action="">';
    echo '<input type="text" name="keyword" value="',htmlspecialchars($keyword),'" placeholder="商品名を入力">';
    echo '<button type="submit">検索</button>';
    echo '</form>';
    ?>
    <p>タグから選ぶ：</p>
    <form method="POST" action="" id="tagForm">
        <input type="hidden" name="keyword" id="tagKeyword">
        <button type="button" onclick="submitTag('')">オススメ</button>
        <button type="button" onclick="submitTag('チーズ')">チーズ</button>
        <button type="button" onclick="submitTag('てりやき')">てりやき</button>
        <button type="button" onclick="submitTag('ポテト')">ポテト</button>
=======
    <h2>SSB</h2>
    <form method="POST" action="">
        <input type="text" name="keyword" id="keywordInput" value="<?= htmlspecialchars($keyword) ?>" placeholder="商品名を入力">
        <button type="submit">検索</button>
>>>>>>> Stashed changes
    </form>

    <p>タグから選ぶ：</p>
    <div>
        <button type="button" onclick="fillKeyword('チーズ')">チーズ</button>
        <button type="button" onclick="fillKeyword('てりやき')">てりやき</button>
        <button type="button" onclick="fillKeyword('ポテト')">ポテト</button>
    </div>

    <script>
    function fillKeyword(tag) {
        document.getElementById('keywordInput').value = tag;
    }
    </script>

<<<<<<< Updated upstream
  <?php
    if ($keyword) {
    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('SELECT * FROM food_data WHERE food_name LIKE ?');
    $sql->execute(['%' . $keyword . '%']);
    $results = $sql->fetchAll();
    if (empty($results)) {
        echo '<p>該当する商品は見つかりませんでした。</p>';
    } else{
        echo '<h2>関連する商品</h2>';
        foreach ($results as $item) {
          echo htmlspecialchars($item['food_name']) . '<br>' . htmlspecialchars($item['price']) . '円';
        }
      }
    }
        $pdo = new PDO($connect, USER, PASS);
        $sqlAll = $pdo->query('SELECT * FROM food_data');
        $allResults = $sqlAll->fetchAll();

        $flag=$pdo->query('SELECT * FROM food_data WHERE recommend_flag=1');
        $recommend_flag = $flag->fetchAll();

        echo '<h2>おすすめ商品</h2>';
        foreach ($recommend_flag as $flags) {
          echo htmlspecialchars($flags['food_name']) . '<br>' . htmlspecialchars($flags['price']) . '円';
        }

        echo '<h2>全ての商品一覧</h2>';
        foreach ($allResults as $item) {
          echo htmlspecialchars($item['food_name']) . '<br>' . htmlspecialchars($item['price']) . '円';
        }
  ?>
=======
    <!-- 検索結果 -->
    <?php if ($searched): ?>
        <h3>検索結果</h3>
        <?php if (empty($keyword) || empty($search_results)): ?>
            <p>該当する商品は見つかりませんでした。</p>
        <?php else: ?>
            <ul>
                <?php foreach ($search_results as $item): ?>
                    <li><?= htmlspecialchars($item['food_name']) ?> - <?= htmlspecialchars($item['price']) ?>円</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
>>>>>>> Stashed changes

    <!-- おすすめ商品 -->
    <h3>おすすめ商品</h3>
    <?php if (!empty($recommend_items)): ?>
        <ul>
            <?php foreach ($recommend_items as $item): ?>
                <li><?= htmlspecialchars($item['food_name']) ?> - <?= htmlspecialchars($item['price']) ?>円</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>おすすめ商品はありません。</p>
    <?php endif; ?>

    <!-- 全商品 -->
    <h3>全商品一覧</h3>
    <?php if (!empty($all_items)): ?>
        <ul>
            <?php foreach ($all_items as $item): ?>
                <li><?= htmlspecialchars($item['food_name']) ?> - <?= htmlspecialchars($item['price']) ?>円</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>商品データがありません。</p>
    <?php endif; ?>
</body>
</html>