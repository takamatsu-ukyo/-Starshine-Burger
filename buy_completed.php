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
$today = date('Y-m-d');

// 購入情報を proceeds_data に登録
try {
  $pdo->beginTransaction();

  foreach ($cart as $item) {
    if ($item['quantity'] > 0) {
      $stmt = $pdo->prepare('INSERT INTO proceeds_data (food_id, purchase_date, number, proceeds) VALUES (:food_id, :purchase_date, :number, :proceeds)');
      $stmt->execute([
        ':food_id' => $item['food_id'],
        ':purchase_date' => $today,
        ':number' => $item['quantity'],
        ':proceeds' => $item['price'] * $item['quantity']
      ]);
    }

    $total += $item['price'] * $item['quantity'];
    $quantity += $item['quantity'];
  }

  $pdo->commit();
} catch (Exception $e) {
  $pdo->rollBack();
  echo "購入処理中にエラーが発生しました: " . htmlspecialchars($e->getMessage());
  exit;
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
      <?php if ($item['quantity'] > 0): ?>
        <p>
          <?= htmlspecialchars($item['food_name']) ?> × <?= $item['quantity'] ?>個 ¥<?= number_format($item['price'] * $item['quantity']) ?>
        </p>
      <?php endif; ?>  
    <?php endforeach; ?>

  <!-- ⑤ 合計金額 -->
  <h3>合計：¥<?= number_format($total) ?></h3>

  <a href="home.php">ホーム画面に戻る</a>
</body>
</html>
