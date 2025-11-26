<?php require 'db-connect.php';?>
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

<!--商品表示枠組み-->
<div class="products">

  <div class="product">
    <div class="product-title">
      <a href="" class="icon-button" title="個別売上を見る">📅</a>
    </div>
    
    <img src="">
  </div>

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