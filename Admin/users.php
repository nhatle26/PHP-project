<?php
require_once 'header.php';

// Lấy danh sách user
$stm = $conn->prepare("SELECT id, username, fullname, email, phone, role FROM users ORDER BY id DESC");
$stm->execute();
$res = $stm->get_result();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Danh sách người dùng</h3>
</div>

<?php
if (isset($_SESSION['success'])) { 
    echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['success']).'</div>'; 
    unset($_SESSION['success']); 
}
if (isset($_SESSION['error'])) { 
    echo '<div class="alert alert-danger">'.htmlspecialchars($_SESSION['error']).'</div>'; 
    unset($_SESSION['error']); 
}
?>

<table class="table table-striped table-bordered align-middle">
  <thead>
    <tr>
      <th>#</th>
      <th>Tài khoản</th>
      <th>Họ và tên</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Role</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['fullname']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= $row['role'] ? 'Admin' : 'User' ?></td>
        <td>
          <a href="edit-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
          <a href="delete-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?');">Xóa</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require_once 'footer.php'; ?>
