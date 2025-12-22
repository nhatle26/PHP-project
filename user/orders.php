<?php
session_start();
require_once __DIR__ . '/../model/connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT id, total, status, date_order, payment_method
    FROM orders
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>

<?php include "../model/header.php"; ?>

<div class="container mt-4">
    <h3>Đơn hàng của tôi</h3>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Ngày đặt</th>
                <th>Thanh toán</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $res->fetch_assoc()): ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><?= $row['date_order'] ?></td>
                <td>
                    <?= $row['payment_method'] === 'bank' ? 'Chuyển khoản' : 'COD' ?>
                </td>
                <td><?= number_format($row['total']) ?> ₫</td>
                <td>
                    <?php
                        if ($row['status'] == 0) echo '<span class="badge bg-warning">Chờ xử lý</span>';
                        elseif ($row['status'] == 1) echo '<span class="badge bg-success">Hoàn thành</span>';
                        else echo '<span class="badge bg-danger">Hủy</span>';
                    ?>
                </td>
                <td>
                    <a href="order-detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">
                        Xem
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "../model/footer.php"; ?>
</body>
</html>
