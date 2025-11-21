<?php
session_start();
require 'db-connect.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
  echo "注文が見つかりません。";
  exit;
}

$total = 0;
$quantity = 0;
foreach ($cart as $item) {
  $total += $item['price'] * $item['quantity'];
  $quantity += $item['quantity'];
}

unset($_SESSION['cart'], $_SESSION['quantity']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入完了画面</title>
    <style>
body{
    font-family: "游ゴシック", "Yu Gothic", sans-serif;
    margin: 0;
    padding: 25px;
    color: #333;
}

/* SSB ロゴ */
h1 {
    color: #ff8c2b;
    text-align: center;
    font-size: 32px;
    margin-bottom: 30px;
    letter-spacing: 2px;
}

/* メッセージ部分 */
p:nth-of-type(1) {
    font-weight: bold;
    text-align: center;
    margin-top: 20px;
}

p:nth-of-type(2) {
    text-align: center;
    font-size: 14px;
    margin-bottom: 25px;
    color: #555;
}

/* 見出し（ご注文内容 / 合計） */
h3 {
    border-bottom: 2px dotted #ffbb7a;
    padding-bottom: 8px;
    margin-top: 30px;
    margin-bottom: 15px;
}

/* 注文リスト */
p {
    margin-left: 10px;
    font-size: 15px;
}

/* 合計金額 */
h3:nth-of-type(2) {
    text-align: right;
    margin-top: 30px;
    font-size: 18px;
}

/* 合計の下に自然に表示される右寄せボタン */
a {
    display: block;
    width: 140px;
    margin-top: 25px;
    margin-left: auto;
    margin-right: 10px;
    padding: 12px 0;
    background: #ff8c2b;
    color: #fff;
    text-decoration: none;
    text-align: center;
    font-weight: bold;
    border-radius: 8px;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.15);
}
a:hover {
    opacity: 0.9;
}


</style>
</head>
<body>
  <!-- ① ロゴ -->
  <h1>SSB</h1>

  <!-- ③ 感謝メッセージ -->
  <p>ご購入ありがとうございます！</p>
  <p>このたびはSSBでお買い物いただき、ありがとうございました。</p>

  <!-- ④ 注文内容 -->
  <h3>ご注文内容</h3>
  <?php foreach ($cart as $item): ?>
    <p><?= htmlspecialchars($item['food_name']) ?> × <?= $item['quantity'] ?>個 ¥<?= number_format($item['price'] * $item['quantity']) ?></p>
  <?php endforeach; ?>


  <!-- ⑤ 合計金額 -->
  <h3>合計：¥<?= number_format($total) ?></h3>

   <a href="home.php">ホーム画面に戻る</a>

</body>
</html>
