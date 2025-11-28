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
    <style>
      <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
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

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product {
            flex: 0 0 calc(25% - 15px); /* 4列表示 */
            min-width: 200px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .product-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .icon-button {
            font-size: 20px;
            text-decoration: none;
            cursor: pointer;
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* レスポンシブ対応 */
        @media (max-width: 992px) {
            .product {
                flex: 0 0 calc(33.333% - 14px); /* 3列表示 */
            }
        }

        @media (max-width: 768px) {
            .product {
                flex: 0 0 calc(50% - 10px); /* 2列表示 */
            }
        }

        @media (max-width: 480px) {
            .product {
                flex: 0 0 100%; /* 1列表示 */
            }
        }

        .tag {
            padding: 10px 20px;
            margin: 10px 20px;
            background-color: #f0f0f0;
            border-radius: 5px;
            display: none;
        }

        #clearTag {
            cursor: pointer;
            margin-left: 10px;
            color: #666;
        }

        #clearTag:hover {
            color: #000;
        }
        </style>
</head>
<body>
 <div class="header">
  <img src="../img/SSBロゴ.png"> <!-- ロゴ -->
  <h1>SSB</h1>
</div>

<div class="search-area">
  <div class="search-box">
    <input type="text" id="searchInput" placeholder="検索">
    <button id="searchBtn" class="search-button">検索</button>
  </div>
</div>

<div class="sales-btn-area">
  <a href="total_sales.php">
    <button id="salesBtn" class="search-button">総合売上</button>
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
        echo '</div>'; 
        echo '</div>'; 

    }
}

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

    document.getElementById("clearTag").addEventListener("click", function () {
        tag.style.display = "none";
        document.getElementById("searchInput").value = "";
    });
  }else {
        tag.style.display = "none";
    }
});
</script>

</body>
</html>