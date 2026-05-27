<?php
require_once __DIR__ . '/db.php';
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); exit;
}

// Handle new computer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_computer') {
    $asset = trim($_POST['asset_tag'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $specs = trim($_POST['specs'] ?? '');
    $location = trim($_POST['location'] ?? '');
    if ($asset && $model) {
        $ins = $pdo->prepare('INSERT INTO computers (asset_tag, model, specs, location) VALUES (?,?,?,?)');
        $ins->execute([$asset, $model, $specs, $location]);
    }
    header('Location: admin.php'); exit;
}

$computers = $pdo->query('SELECT * FROM computers ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$borrows = $pdo->query('SELECT b.*, c.asset_tag, c.model FROM borrows b LEFT JOIN computers c ON b.computer_id = c.id ORDER BY b.borrowed_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin — IT Inventory</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <a href="index.php">← Home</a> | <a href="logout.php">Logout</a>
  <h2>Admin Dashboard</h2>

  <section>
    <h3>Add Computer</h3>
    <form method="post">
      <input type="hidden" name="action" value="add_computer">
      <label>Asset Tag<br><input name="asset_tag" required></label>
      <label>Model<br><input name="model" required></label>
      <label>Specs<br><textarea name="specs"></textarea></label>
      <label>Location<br><input name="location"></label>
      <p><button type="submit">Add</button></p>
    </form>
  </section>

  <section>
    <h3>Computers</h3>
    <table>
      <thead><tr><th>ID</th><th>Asset</th><th>Model</th><th>Location</th><th>Status</th></tr></thead>
      <tbody>
      <?php foreach ($computers as $c): ?>
        <tr>
          <td><?=htmlspecialchars($c['id'])?></td>
          <td><?=htmlspecialchars($c['asset_tag'])?></td>
          <td><?=htmlspecialchars($c['model'])?></td>
          <td><?=htmlspecialchars($c['location'])?></td>
          <td><?=htmlspecialchars($c['status'])?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>

  <section>
    <h3>Borrow Records</h3>
    <table>
      <thead><tr><th>ID</th><th>Computer</th><th>Borrower</th><th>Borrowed At</th><th>Due</th><th>Returned</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach ($borrows as $b): ?>
        <tr>
          <td><?=htmlspecialchars($b['id'])?></td>
          <td><?=htmlspecialchars($b['asset_tag']).' / '.htmlspecialchars($b['model'])?></td>
          <td><?=htmlspecialchars($b['borrower_name']).' ('.htmlspecialchars($b['borrower_id']).')'?></td>
          <td><?=htmlspecialchars($b['borrowed_at'])?></td>
          <td><?=htmlspecialchars($b['due_at'])?></td>
          <td><?=htmlspecialchars($b['returned_at'])?></td>
          <td>
            <?php if (empty($b['returned_at'])): ?>
              <form method="post" action="return.php" style="display:inline">
                <input type="hidden" name="borrow_id" value="<?=htmlspecialchars($b['id'])?>">
                <button type="submit">Mark returned</button>
              </form>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>

</body>
</html>
