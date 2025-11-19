<?php require 'db-connect.php';?>
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
  <input type="text" id="searchInput" placeholder="検索">
  <button id="searchBtn">検索</button>
</div>

<div class="sales-btn-area">
  <a href="total_sales.php">
    <button id="salesBtn">総合売上</button>
  </a>
</div>

<p class="tag" id="searchTag"></p>

<!--商品表示枠組み-->
<div class="products">
<?php
// 検索キーワード取得
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";

// キーワードがあるときだけ検索実行
if ($keyword !== "") {
    $sql = "SELECT * FROM food_data WHERE name LIKE :keyword ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":keyword", "%{$keyword}%", PDO::PARAM_STR);
    $stmt->execute();
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($foods) === 0) {
        echo "<p>該当する商品がありません。</p>";
    } else {
        foreach ($foods as $food) {
            echo '<div class="product">';
            echo '  <div class="product-title">';
            echo '    <h2>' . htmlspecialchars($food['name']) . '</h2>';
            echo '    <a href="sales_detail.php?id=' . htmlspecialchars($food['id']) . '" class="icon-button" title="個別売上を見る">📅</a>';
            echo '  </div>';
            echo '  <div class="product-img">';
            echo '    <img src="' . htmlspecialchars($food['image_path']) . '" alt="' . htmlspecialchars($food['name']) . '">';
            echo '  </div>';
            echo '</div>';
        }
    }
} else {
    echo "<p>検索キーワードを入力してください。</p>";
}
?>
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