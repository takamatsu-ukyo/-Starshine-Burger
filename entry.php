<?php session_start();

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
</head>
<body>
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
    </form>

  <script>
    function submitTag(tag) {
      document.getElementById('tagKeyword').value = tag;
      document.getElementById('tagForm').submit();
    }
  </script>

  <?php
    require 'db-connect.php';

    if ($keyword) {
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


</body>
</html>