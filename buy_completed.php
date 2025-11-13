<?php
session_start();
require 'db-connect.php';

// 最新の購入（food_data）の1件を取得
$sql = "SELECT * FROM food_data ORDER BY id DESC LIMIT 1";
$stmt = $pdo->query($sql);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "注文が見つかりません。";
    exit;
}

// 数量をセッションから取得（なければ1）
$quantity = isset($_SESSION['quantity']) ? (int)$_SESSION['quantity'] : 1;

// 合計金額を計算
$total = (int)$order['price'] * $quantity;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入完了画面</title>
</head>
<body>

  <!-- ② ×ボタン -->
  <a href="home.php">×</a>

  <!-- ① ロゴ -->
  <h1>SSB</h1>

  <!-- ③ 感謝メッセージ -->
  <p>ご購入ありがとうございます！</p>
  <p>このたびはSSBでお買い物いただき、ありがとうございました。</p>

  <!-- ④ 注文内容 -->
  <h3>ご注文内容</h3>
  <p>
    <?= htmlspecialchars($order['food_name']) ?>　
    <?= $quantity ?>点　
    ¥<?= number_format((int)$order['price'] * $quantity) ?>
  </p>

  <!-- ⑤ 合計金額 -->
  <h3>合計：¥<?= number_format($total) ?></h3>

</body>
</html>
