<?php
require_once 'header.php';

// Lấy danh sách user
$stm = $conn->prepare("SELECT id, username, fullname, email, phone, role, is_locked FROM users ORDER BY id DESC");
$stm->execute();
$res = $stm->get_result();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Danh sách người dùng</h3>
</div>

<?php
if (isset($_SESSION['success'])) { 
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.htmlspecialchars($_SESSION['success']).'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'; 
    unset($_SESSION['success']); 
}
if (isset($_SESSION['error'])) { 
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.htmlspecialchars($_SESSION['error']).'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'; 
    unset($_SESSION['error']); 
}
?>

<table class="table table-striped table-bordered align-middle">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Tài khoản</th>
      <th>Họ và tên</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Role</th>
      <th>Trạng thái</th>
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
        <td><?= $row['role'] ? '<span class="badge bg-danger">Admin</span>' : '<span class="badge bg-info">User</span>' ?></td>
        <td>
          <?php if ($row['is_locked']): ?>
            <span class="badge bg-secondary">🔒 Khóa</span>
          <?php else: ?>
            <span class="badge bg-success">🔓 Hoạt động</span>
          <?php endif; ?>
        </td>
        <td>
          <form method="POST" action="lock-user.php" style="display:inline;">
            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
            <?php if ($row['is_locked']): ?>
              <button type="submit" name="action" value="unlock" class="btn btn-sm btn-success" onclick="return confirm('Mở khóa tài khoản này?');">Mở khóa</button>
            <?php else: ?>
              <button type="submit" name="action" value="lock" class="btn btn-sm btn-warning" onclick="return confirm('Khóa tài khoản này?');">Khóa</button>
            <?php endif; ?>
          </form>
          <a href="edit-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
          <a href="delete-user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?');">Xóa</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require_once 'footer.php'; ?>
