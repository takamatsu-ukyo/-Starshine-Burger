<?php 
session_start();
if (isset($_POST['keyword'])) {
    $_SESSION['keyword'] = $_POST['keyword'];
}

require_once 'db-connect.php';

// セッションからキーワードを取得（なければ空文字）
$keyword = isset($_SESSION['keyword']) ? $_SESSION['keyword'] : '';

$sql = "SELECT food_id, food_name FROM food_data";
$stmt = $pdo->query($sql);
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理ホーム</title>
</head>
<body>
 <div class="header">
  <img src=""> <!-- ロゴ -->
  <h1>SSB</h1>
</div>

<div class="search-area">
    <?php
    echo '<form method="POST" action="">';
    echo '<input type="text" name="keyword" id="searchInput" value="',htmlspecialchars($keyword),'" placeholder="商品名を入力">';
    echo '<button type="submit"  class="search-button">検索</button>';
    echo '</form>'
    ?>
</div>

<div class="sales-btn-area">
  <a href="total_sales.php">
    <button id="salesBtn">総合売上</button>
  </a>
</div>

<p class="tag" id="searchTag"></p>

<?php

    if ($keyword) {
    $sql = $pdo->prepare('SELECT * FROM food_data WHERE food_name LIKE ?');
    $sql->execute(['%' . $keyword . '%']);
    $results = $sql->fetchAll();
    
    if (empty($results)) {
        echo '<div class="products-section">';
        echo '<p style="text-align: center;">該当する商品は見つかりませんでした。</p>';
        echo '</div>';
    } else {
        echo '<div class="products-section">';
        echo '<h2>関連する商品</h2>';
        echo '<div class="products-grid">';
        
        foreach ($results as $item) {
            echo '<div class="product">';
            echo  '<div class="product-title">';
            echo   '<a href="individual_sales.php?food_id=' . urlencode($item['food_id']) . '" class="icon-button" title="個別売上を見る">';
            echo     htmlspecialchars($item['food_name']);
            echo   '</a>';
            echo  '</div>';
            echo  '<a href="individual_sales.php?food_id=' . urlencode($item['food_id']) . '">';
            echo   '<img src="image/' . htmlspecialchars($item['food_id']) . '.png" alt="' . htmlspecialchars($item['food_name']) . '" style="width:150px;height:auto;">';
            echo  '</a>';
            echo '</div>';

        }
    }
}

$sqlAll = $pdo->query('SELECT * FROM food_data');
$allResults = $sqlAll->fetchAll();

$flag = $pdo->query('SELECT * FROM food_data WHERE recommend_flag=1');
$recommend_flag = $flag->fetchAll();
?>
<!--商品表示枠組み-->
<h2>商品一覧</h2>
<div class="products">
  <?php foreach ($foods as $food): ?>
    <div class="product">
      <div class="product-title">
        <a href="individual_sales.php?food_id=<?= urlencode($food['food_id']) ?>" class="icon-button" title="個別売上を見る">
          <?= htmlspecialchars($food['food_name']) ?>
        </a>
      </div>
      <a href="individual_sales.php?food_id=<?= urlencode($food['food_id']) ?>">
        <img src="image/<?= htmlspecialchars($food['food_id']) ?>.png" alt="<?= htmlspecialchars($food['food_name']) ?>" style="width:150px;height:auto;">
      </a>
    </div>
  <?php endforeach; ?>
</div>

<script>
//　検索タグ表示
document.getElementById("searchBtn").addEventListener("click", function () {
    const keyword = document.getElementById("searchInput").value.trim();
    const tag = document.getElementById("searchTag");

    if (keyword !== "") {
        tag.innerHTML = `${keyword} <span id="clearTag">✕</span>`;
        tag.style.display = "block";
    } else {
        tag.style.display = "none";
    }

    document.getElementById("clearTag").addEventListener("click", function () {
        tag.style.display = "none";
        document.getElementById("searchInput").value = "";
    });
});
</script>

</body>
</html>