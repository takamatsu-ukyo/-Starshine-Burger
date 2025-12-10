
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
    ORDER BY pd.purchase_date DESC
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
    <style>
body {
    font-family: "Yu Gothic", sans-serif;
    background: #fafafa;
    margin: 0;
    padding: 0;
}

/* 見出し */
h2 {
    margin: 20px;
    font-size: 24px;
}

/* フォーム */
form {
    background: #fff;
    padding: 15px;
    margin: 20px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

label {
    margin-right: 10px;
}

input[type="date"] {
    padding: 5px 10px;
    margin-right: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background: #ffb347; /* 画像と同じオレンジ */
    color: #fff;
    border: none;
    padding: 6px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}
button:hover {
    opacity: 0.8;
}

/* テーブル */
table {
    width: 95%;
    border-collapse: collapse;
    margin: 20px auto;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

th {
    background: #f7a726; /* 画像と同じ濃いオレンジ */
    color: #fff;
    padding: 12px;
    font-size: 16px;
}

td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

/* 交互の背景色 */
tr:nth-child(even) {
    background: #fdf5e6; /* ほんのり薄いクリーム色 */
}

/* 総計部分の枠 */
.summary {
    width: 93%;
    margin: 20px auto;
    padding: 20px;
    background: #fff9e6; /* 画像の薄黄色 */
    border-left: 6px solid #f7a726;
    border-radius: 6px;
    font-size: 18px;
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
</style>

</head>
<body>
    <a href="manager_home.php" class="back-btn">← 戻る</a>
    <h2>総合売上一覧</h2>
    <form method="post">
        <label for="from">開始日：</label>
        <input type="date" name="from" id="from" value="<?= htmlspecialchars($_POST['from'] ?? '') ?>">
        <label for="to">終了日：</label>
        <input type="date" name="to" id="to" value="<?= htmlspecialchars($_POST['to'] ?? '') ?>">
        <button type="submit">検索</button>
    </form>
<div class="summary">
    <h2>全商品の総合売上</h2>
    <p>総売上金額：¥<?= number_format($summary['all_money'] ?? 0) ?></p>
</div>

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

</body>
</html>