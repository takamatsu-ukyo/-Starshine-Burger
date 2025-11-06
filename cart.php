<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート画面</title>
</head>
<body>
    <?php
session_start();
require_once 'db.php';

// カートに追加処理（home.php などからPOSTされた場合）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['food_id'])) {
    $food_id = (int)$_POST['food_id'];

    $stmt = $pdo->prepare("SELECT * FROM food_data WHERE food_id = ?");
    $stmt->execute([$food_id]);
    $food = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($food) {
        $_SESSION['cart'][] = $food;
    }
}

// カート削除
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    foreach ($_SESSION['cart'] ?? [] as $index => $item) {
        if ($item['food_id'] == $remove_id) {
            unset($_SESSION['cart'][$index]);
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location: index.php?page=cart');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$total = array_sum(array_column($cart, 'price'));
?>

<div class="cart-container">
  <h2>🛒 カート画面</h2>

  <?php if (empty($cart)): ?>
    <p>カートに商品がありません。</p>
    <a href="index.php?page=home" class="btn">商品一覧へ</a>
  <?php else: ?>
    <table class="cart-table">
      <tr><th>商品名</th><th>価格</th><th></th></tr>
      <?php foreach ($cart as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['food_name']) ?></td>
          <td>¥<?= number_format($item['price']) ?></td>
          <td><a href="index.php?page=cart&remove=<?= $item['food_id'] ?>" class="remove-btn">削除</a></td>
        </tr>
      <?php endforeach; ?>
      <tr class="total-row">
        <td>合計</td>
        <td>¥<?= number_format($total) ?></td>
        <td></td>
      </tr>
    </table>

    <div class="cart-actions">
      <a href="index.php?page=checkout" class="btn">購入へ進む</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>p