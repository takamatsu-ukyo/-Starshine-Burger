<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: manager_login.php");
    exit;
}

require 'db-connect.php';

// food_id が指定されていなければエラー
if (!isset($_GET['food_id'])) {
    exit("商品が指定されていません。");
}

$food_id = $_GET['food_id'];
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

$where = "WHERE food_id = ?";
$params = [$food_id];

if ($from && $to) {
    $where .= " AND purchase_date BETWEEN ? AND ?";
    $params[] = $from;
    $params[] = $to;
} elseif ($from) {
    $where .= " AND purchase_date >= ?";
    $params[] = $from;
} elseif ($to) {
    $where .= " AND purchase_date <= ?";
    $params[] = $to;
}

// 商品名取得（タイトル用）
$sql_name = "SELECT food_name FROM food_data WHERE food_id = ?";
$stmt = $pdo->prepare($sql_name);
$stmt->execute([$food_id]);
$food = $stmt->fetch(PDO::FETCH_ASSOC);

$food_name = $food ? $food['food_name'] : "不明な商品";


// 商品別の売上取得
$sql = "
    SELECT 
        purchase_date,
        number,
        proceeds
    FROM proceeds_data
    $where
    ORDER BY purchase_date DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productSales = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql_total_item = "
    SELECT
        SUM(number) AS total_count_item,
        SUM(proceeds) AS total_sales_item
    FROM proceeds_data
    $where
";
$stmt = $pdo->prepare($sql_total_item);
$stmt->execute($params);
$totalItem = $stmt->fetch(PDO::FETCH_ASSOC);

$total_count_item = $totalItem['total_count_item'] ?? 0;
$total_sales_item = $totalItem['total_sales_item'] ?? 0;



// 総合売上を取得（販売総数 / 総売上金額）
$sql_total_all = "
    SELECT
        SUM(number) AS total_count_all,
        SUM(proceeds) AS total_sales_all
    FROM proceeds_data
";
$stmt = $pdo->query($sql_total_all);
$totalAll = $stmt->fetch(PDO::FETCH_ASSOC);

$total_count_all = $totalAll['total_count_all'] ?? 0;
$total_sales_all = $totalAll['total_sales_all'] ?? 0;




?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($food_name) ?> の売上一覧</title>
    <style>
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }
        th,td{
            padding:10px;
            border-bottom:1px solid #ddd;
        }
        th{
            background:#ffa726;
            color:#fff;
        }
        .back-btn{
            display:inline-block;
            padding:8px 15px;
            background:#444;
            color:#fff;
            text-decoration:none;
            border-radius:4px;
            margin-bottom:10px;
        }

        .total-all-box{
            margin-top:40px;
            padding:15px;
            border:1px solid #ccc;
            background:#fff8e5;
            border-radius:5px;
        }
        .total-all-box h2{
            margin-top:0;
        }
    </style>
</head>
<body>

<a href="manager_home.php" class="back-btn">← 戻る</a>

<h2><?= htmlspecialchars($food_name) ?> の売上一覧</h2>

<form method="get" action="">
  <input type="hidden" name="food_id" value="<?= htmlspecialchars($food_id) ?>">
  <label for="from">開始日：</label>
  <input type="date" name="from" id="from" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
  <label for="to">終了日：</label>
  <input type="date" name="to" id="to" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
  <button type="submit">絞り込む</button>
</form>
<!-- 売上 ▼ -->
<div class="total-all-box">
    <h2><?= htmlspecialchars($food_name) ?> の合計売上</h2>
    <p>販売総数：<?= number_format($total_count_item) ?> 個</p>
    <p>総売上金額：¥<?= number_format($total_sales_item) ?></p>
</div>

<table>
    <tr>
        <th>購入日</th>
        <th>数量</th>
        <th>売上金額</th>
    </tr>

    <?php if (count($productSales) === 0): ?>
        <tr><td colspan="3">売上データがありません。</td></tr>
    <?php else: ?>
        <?php foreach ($productSales as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['purchase_date']) ?></td>
            <td><?= htmlspecialchars($row['number']) ?> 個</td>
            <td>¥<?= number_format($row['proceeds']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>



</body>
</html>
