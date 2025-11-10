<?php require 'db-connect.php';

// 商品ID取得
$id = $_GET['id'] ?? 0;

// 日付
$selectedDate = $_GET['date'] ?? date('Y-m-d');

// 日付一覧を取得
$sql = "SELECT DISTINCT purchase_date FROM proceeds_date WHERE food_id = ? ORDER BY purchase_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$dateList = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 選択された日付の売上データを取得
$sql = "SELECT food_name, number, proceeds 
        FROM proceeds_date 
        WHERE food_id = ? AND purchase_date = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $selectedDate]);
$individualSales = $stmt->fetchAll();

// 今日
$today = date('Y-m-d');
$sql = "SELECT SUM(proceeds) AS money, SUM(number) AS qty 
        FROM proceeds_date 
        WHERE food_id = ? AND purchase_date = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $today]);
$todayData = $stmt->fetch();

// 今月
$month = date('Y-m');
$sql = "SELECT SUM(proceeds) AS money, SUM(number) AS qty 
        FROM proceeds_date 
        WHERE food_id = ? AND DATE_FORMAT(purchase_date, '%Y-%m') = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $month]);
$monthData = $stmt->fetch();

// 累計
$sql = "SELECT SUM(proceeds) AS money, SUM(number) AS qty 
        FROM proceeds_date 
        WHERE food_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$totalData = $stmt->fetch();

// 商品名
$sql = "SELECT food_name FROM proceeds_date WHERE food_id = ? LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$product = $stmt->fetch();
$name = $product['food_name'] ?? "商品名不明";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個別売上</title>
</head>
<body>
    <h2><?= htmlspecialchars($name) ?> の個別売上</h2>

<form method="get">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
    <label for="date">日付を選択：</label>
    <select name="date" id="date" onchange="this.form.submit()">
        <?php foreach ($dateList as $date): ?>
            <option value="<?= htmlspecialchars($date) ?>" <?= $date === $selectedDate ? 'selected' : '' ?>>
                <?= htmlspecialchars($date) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>商品名</th>
        <th>販売数</th>
        <th>売上金額</th>
    </tr>
    <?php if (count($individualSales) === 0): ?>
        <tr><td colspan="3">データがありません。</td></tr>
    <?php else: ?>
        <?php foreach ($individualSales as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['food_name']) ?></td>
            <td><?= htmlspecialchars($row['number']) ?> 個</td>
            <td>¥<?= number_format($row['proceeds']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<div class="summary">
    <h2><?= htmlspecialchars($name) ?> の売上</h2>
    <p>本日売上：¥<?= number_format($todayData['money'] ?? 0) ?>（<?= $todayData['qty'] ?? 0 ?>個）</p>
    <p>今月売上：¥<?= number_format($monthData['money'] ?? 0) ?>（<?= $monthData['qty'] ?? 0 ?>個）</p>
    <p>累計売上：¥<?= number_format($totalData['money'] ?? 0) ?>（<?= $totalData['qty'] ?? 0 ?>個）</p>
</div>

</body>
</html>