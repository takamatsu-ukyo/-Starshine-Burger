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
