<?php
session_start();
require 'db-connect.php';

// 最新の注文データを1件取得（テーブル名修正）
$sql = "SELECT * FROM food_data ORDER BY id DESC LIMIT 1";
$stmt = $pdo->query($sql);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "注文が見つかりません。";
    exit;
}

// ユーザーIDを使って、注文した商品一覧を取得する想定
$sql2 = "SELECT * FROM food_data WHERE id = ?";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([$_SESSION['id']]);
$details = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// 合計金額を計算
$total = 0;
foreach ($details as $item) {
    $total += $item['price'] * $_POST["count"];
}
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
  <?php if ($details): ?>
    <?php foreach ($details as $item): ?>
      <p>
        <?= htmlspecialchars($item['food_name']) ?>
       ¥<?= number_format((int)$item['price'] * $_POST["count"]) ?>
      </p>
    <?php endforeach; ?>
  <?php else: ?>
    <p>注文の明細が見つかりません。</p>
  <?php endif; ?>

  <!-- ⑤ 合計金額 -->
  <h3>小計：¥<?= number_format($total) ?></h3>

</body>
</html>
