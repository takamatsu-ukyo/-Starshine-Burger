<?php
session_start();

// カートの中身取得
$cart = $_SESSION['cart'] ?? [];

// カートが空ならリダイレクト
if (empty($cart)) {
  header('Location: home.php?page=cart');
  exit;
}

$total = 0;
foreach ($cart as $item) {
  $total += $item['price'] * $item['quantity'];
}

$quantity = array_sum(array_column($cart, 'quantity'));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>購入確認 | SSB</title>
  <style>
    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      background: #fff;
      margin: 0;
      padding: 0;
      color: #333;
    }
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
      background: #fff;
      border-bottom: 2px solid #f1f1f1;
    }
    header img {
      height: 40px;
    }
    header h1 {
      color: #ff7b00;
      font-size: 22px;
      margin: 0;
    }
    .checkout-container {
      padding: 20px;
      max-width: 400px;
      margin: 0 auto;
    }
    h2 {
      color: #ff7b00;
      border-bottom: 2px solid #ff7b00;
      display: inline-block;
      margin-bottom: 20px;
    }
    .item {
      display: flex;
      align-items: center;
      border-bottom: 1px solid #eee;
      padding: 10px 0;
    }
    .item img {
      width: 60px;
      height: 60px;
      border-radius: 6px;
      margin-right: 10px;
      object-fit: cover;
    }
    .item-info {
      flex: 1;
    }
    .item-info p {
      margin: 0;
    }
    select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 10px;
    }
    .summary {
      background: #fafafa;
      border: 1px solid #eee;
      border-radius: 8px;
      padding: 10px 15px;
      margin-top: 20px;
    }
    .summary p {
      margin: 6px 0;
    }
    .btn {
      display: block;
      width: 100%;
      text-align: center;
      background: #ff7b00;
      color: #fff;
      text-decoration: none;
      padding: 12px 0;
      border-radius: 8px;
      font-weight: bold;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <header>
    <img src="images/ssb-burger.png" alt="SSBロゴ">
    <h1>SSB</h1>
  </header>

  <div class="checkout-container">
    <h2>ご購入内容</h2>

    <?php foreach ($cart as $item): ?>
      <div class="item">
        <?php if ($item['quantity'] != 0): ?>
          <img src="<?= htmlspecialchars($item['image_path'] ?? 'image/'.htmlspecialchars($item['food_id']).'.png') ?>" alt="<?= htmlspecialchars($item['food_name']) ?>">
          <div class="item-info">
            <p><?= htmlspecialchars($item['food_name']) ?></p>
            <p>¥<?= number_format($item['price']) ?> × <?= $item['quantity'] ?>個</p>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

    <label for="payment">お支払い方法</label>
    <select id="payment" name="payment">
      <option value="PayPay">PayPay</option>
      <option value="クレジットカード">クレジットカード</option>
      <option value="現金">現金</option>
    </select>

    <div class="summary">
      <p>合計：¥<?= number_format($total) ?></p>
      <p>商品数：<?= $quantity ?>個</p>
    </div>

    <form action="buy_completed.php?page=checkout_complete" method="post">
      <button type="submit" class="btn">購入</button>
    </form>
    <form action="cart.php?page=checkout_complete" method="post">
      <button type="submit" class="btn">カート画面へ</button>
    </form>
  </div>
</body>
</html>