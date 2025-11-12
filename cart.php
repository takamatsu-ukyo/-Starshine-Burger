<?php
session_start();
require_once 'db-connect.php';

// カート追加（home.php から飛んできたとき用）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['food_id'])) {
    $food_id = (int)$_POST['food_id'];

    $stmt = $pdo->prepare("SELECT * FROM food_data WHERE food_id = ?");
    $stmt->execute([$food_id]);
    $food = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($food) {
    $found = false;
    foreach ($_SESSION['cart'] ?? [] as $i => $item) {
        if ($item['food_id'] == $food_id) {
            $_SESSION['cart'][$i]['quantity'] = ($item['quantity'] ?? 1) + 1;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $food['quantity'] = 1;
        $_SESSION['cart'][] = $food;
    }

    header('Location: cart.php');
    exit;
  }
}

// カート削除
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    foreach ($_SESSION['cart'] ?? [] as $i => $item) {
        if ($item['food_id'] == $remove_id) {
            unset($_SESSION['cart'][$i]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']); // 配列再構成
    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * ($item['quantity'] ?? 1);
}

// 個数を増やす
if (isset($_GET['increase'])) {
    $id = (int)$_GET['increase'];
    foreach ($_SESSION['cart'] ?? [] as $i => $item) {
        if ($item['food_id'] == $id) {
            $_SESSION['cart'][$i]['quantity'] = ($item['quantity'] ?? 1) + 1;
            break;
        }
    }
    header('Location: cart.php');
    exit;
}

// 個数を減らす
if (isset($_GET['decrease'])) {
    $id = (int)$_GET['decrease'];
    foreach ($_SESSION['cart'] ?? [] as $i => $item) {
        if ($item['food_id'] == $id) {
            $newQty = ($item['quantity'] ?? 1) - 1;
            $_SESSION['cart'][$i]['quantity'] = $newQty;
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']); 
    header('Location: cart.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート画面</title>
</head>
<body>
<div class="cart-container">
  <h2>🛒 カート</h2>

  <?php if (empty($cart)): ?>
    <p>カートに商品がありません。</p>
    <a href="home.php?page=home" class="btn">商品一覧へ</a>
  <?php else: ?>
    <table class="cart-table">
      <tr><th>商品名</th><th>価格</th><th>個数</th><th>小計</th><th></th></tr>
      <?php foreach ($cart as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['food_name']) ?></td>
          <td>¥<?= number_format($item['price']) ?></td>
          <td><?= $item['quantity'] ?? 1 ?> 個
        <a href="cart.php?increase=<?= $item['food_id'] ?>">＋</a>
        <a href="cart.php?decrease=<?= $item['food_id'] ?>">−</a>
        </td>
          <td>¥<?= number_format($item['price'] * ($item['quantity'] ?? 1)) ?></td>
          <td><a href="cart.php?remove=<?= $item['food_id'] ?>" class="remove-btn">削除</a></td>
        </tr>
      <?php endforeach; ?>
      <tr class="total-row">
        <td colspan="3">合計</td>
        <td>¥<?= number_format($total) ?></td>
        <td></td>
      </tr>
    </table>

    <div class="cart-actions">
      <a href="buy_check.php?page=checkout" class="btn">購入へ進む</a>
      <a href="home.php?page=home" class="btn">商品一覧へ</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>

