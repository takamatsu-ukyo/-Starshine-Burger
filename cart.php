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
$totalItems = 0;
foreach ($cart as $item) {
    $quantity = $item['quantity'] ?? 1;
    $total += $item['price'] * $quantity;
    $totalItems += $quantity;
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
    <title>カート画面 - SSB</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
        }

        .logo-text {
            font-size: 32px;
            font-weight: bold;
            color: #ff8c42;
        }

        .menu-icon {
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .search-section {
            padding: 15px 20px;
            background: white;
            display: flex;
            gap: 10px;
        }

        .home-btn {
            display: inline-block;
            margin: 15px 20px;
            background: #ff8c42;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .cart-container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .cart-item {
            background: white;
            border: 3px solid #4da6ff;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .item-image {
            width: 100%;
            height: 150px;
            background: #ddd;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .item-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .item-name {
            font-size: 16px;
            font-weight: bold;
        }

        .item-price {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .item-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #ff8c42;
            border-radius: 20px;
            padding: 5px 10px;
        }

        .qty-btn {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0 10px;
            font-weight: bold;
        }

        .quantity {
            color: white;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }

        .remove-btn {
            background: #ff8c42;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }

        .cart-summary {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .checkout-btn {
            background: #ff8c42;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 20px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 15px;
        }

        .empty-cart p {
            margin-bottom: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-section">
            <img src="img/SSBロゴ.png" alt="Logo" class="logo-icon">
            <div class="logo-text">SSB</div>
        </div>
    </div>

    <a href="home.php?page=home" class="home-btn">ホーム</a>

    <div class="cart-container">
        <?php if (empty($cart)): ?>
            <div class="empty-cart">
                <p>カートに商品がありません。</p>
                <a href="home.php?page=home" class="checkout-btn">商品一覧へ</a>
            </div>
        <?php else: ?>
            <?php foreach ($cart as $item): ?>
                <div class="cart-item">
                    <img src="image/<?= htmlspecialchars($item['food_id']) ?>.png" alt="<?= htmlspecialchars($item['food_name']) ?>" class="item-image">
                    <div class="item-info">
                        <div class="item-name"><?= htmlspecialchars($item['food_name']) ?></div>
                        <div class="item-price">¥<?= number_format($item['price']) ?></div>
                    </div>
                    <div class="item-controls">
                        <div class="quantity-controls">
                            <a href="cart.php?decrease=<?= $item['food_id'] ?>" class="qty-btn">-</a>
                            <span class="quantity"><?= $item['quantity'] ?? 1 ?></span>
                            <a href="cart.php?increase=<?= $item['food_id'] ?>" class="qty-btn">+</a>
                        </div>
                        <a href="cart.php?remove=<?= $item['food_id'] ?>" class="remove-btn">削除</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>小計：</span>
                    <span>¥<?= number_format($total) ?></span>
                </div>
                <div class="summary-row">
                    <span>商品数：</span>
                    <span><?= $totalItems ?>個</span>
                </div>
                <a href="buy_check.php?page=checkout" class="checkout-btn">購入</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
