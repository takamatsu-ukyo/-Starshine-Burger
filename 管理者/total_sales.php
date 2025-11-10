<?php
require 'db-connect.php';

// === 全商品の売上データ取得 ===
$sql = "
    SELECT 
        food_id,
        food_name,
        SUM(number) AS total_qty,
        SUM(proceeds) AS total_money
    FROM proceeds_date
    GROUP BY food_id, food_name
    ORDER BY total_money DESC
";
$stmt = $pdo->query($sql);
$totalSales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === 総合 ===
$sql = "
    SELECT 
        SUM(number) AS all_qty,
        SUM(proceeds) AS all_money
    FROM proceeds_date
";
$summary = $pdo->query($sql)->fetch();
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

<table>
    <tr>
        <th>商品ID</th>
        <th>商品名</th>
        <th>販売総数</th>
        <th>総売上金額</th>
    </tr>
    <?php if (count($totalSales) === 0): ?>
        <tr><td colspan="5">データがありません。</td></tr>
    <?php else: ?>
        <?php foreach ($totalSales as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['food_id']) ?></td>
            <td><?= htmlspecialchars($row['food_name']) ?></td>
            <td><?= htmlspecialchars($row['total_qty']) ?> 個</td>
            <td>¥<?= number_format($row['total_money']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<div class="summary">
    <h2>全商品の総合売上</h2>
    <p>販売総数：<?= htmlspecialchars($summary['all_qty'] ?? 0) ?> 個</p>
    <p>総売上金額：¥<?= number_format($summary['all_money'] ?? 0) ?></p>
</div>

</body>
</html>