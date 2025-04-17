<?php
$page_title = "Manage Users";
$allowed_roles = ['admin']; // Restrict to admins
require_once '../includes/auth_check.php';
require_once '../includes/header.php';

// Fetch all users
$users = $pdo->query("SELECT * FROM Users ORDER BY role")->fetchAll();
?>

<table>
  <thead>
    <tr>
      <th>Username</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= $user['role'] ?></td>
        <td>
          <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-edit">
            Edit
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>