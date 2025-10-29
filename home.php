<?php require 'db-connect.php';?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム画面</title>
</head>
<body>
    <h2>SSB</h2>
    <form method="POST" action="">
    <input type="text" name="keyword" id="keywordInput" value="<?= htmlspecialchars($keyword) ?>" placeholder="商品名を入力">
    <button type="submit">検索</button>
    </form>
    <?php
    // キーワード取得（POST）
    $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
    ?>


    <button>チーズ</button>
    <button>てりやき</button>
    <button>ポテト</button>
</body>
</html>