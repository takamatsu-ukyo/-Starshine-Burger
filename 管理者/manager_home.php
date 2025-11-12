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