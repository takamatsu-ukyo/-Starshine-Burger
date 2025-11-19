
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db-connect.php';

$from = $_POST['from'] ?? null;
$to = $_POST['to'] ?? null;

$where = '';
$params = [];

if ($from && $to) {
    $where = "WHERE pd.purchase_date BETWEEN :from AND :to";
    $params = ['from' => $from, 'to' => $to];
} elseif ($from) {
    $where = "WHERE pd.purchase_date >= :from";
    $params = ['from' => $from];
} elseif ($to) {
    $where = "WHERE pd.purchase_date <= :to";
    $params = ['to' => $to];
}

// 商品別売上データ
$sql = "
    SELECT 
        pd.food_id,
        f.food_name,
        pd.purchase_date,
        pd.number,
        pd.proceeds
    FROM proceeds_data pd
    JOIN food_data f ON pd.food_id = f.food_id
    $where
    ORDER BY pd.purchase_date ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$totalSales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 総合売上
$where = '';
$params = [];

if ($from && $to) {
    $where = "WHERE purchase_date BETWEEN :from AND :to";
    $params = ['from' => $from, 'to' => $to];
} elseif ($from) {
    $where = "WHERE purchase_date >= :from";
    $params = ['from' => $from];
} elseif ($to) {
    $where = "WHERE purchase_date <= :to";
    $params = ['to' => $to];
}

$sql = "
    SELECT 
        SUM(number) AS all_qty,
        SUM(proceeds) AS all_money
    FROM proceeds_data
    $where
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$summary = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トータル売上</title>
</head>
<body>
    <h2>総合売上一覧</h2>
    <form method="post">
        <label for="from">開始日：</label>
        <input type="date" name="from" id="from" value="<?= htmlspecialchars($_POST['from'] ?? '') ?>">
        <label for="to">終了日：</label>
        <input type="date" name="to" id="to" value="<?= htmlspecialchars($_POST['to'] ?? '') ?>">
        <button type="submit">検索</button>
    </form>

<table>
    <tr>
        <th>商品ID</th>
        <th>商品名</th>
        <th>販売数</th>
        <th>売上金額</th>
        <th>販売日</th>
    </tr>
    <?php if (count($totalSales) === 0): ?>
        <tr><td colspan="5">データがありません。</td></tr>
    <?php else: ?>
        <?php foreach ($totalSales as $row): ?>
        <tr>
        <td><?= htmlspecialchars($row['food_id']) ?></td>
        <td><?= htmlspecialchars($row['food_name']) ?></td>
        <td><?= htmlspecialchars($row['number']) ?> 個</td>
        <td>¥<?= number_format($row['proceeds']) ?></td>
        <td><?= htmlspecialchars($row['purchase_date']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<div class="summary">
    <h2>全商品の総合売上</h2>
    <p>総売上金額：¥<?= number_format($summary['all_money'] ?? 0) ?></p>
</div>

</body>
</html>