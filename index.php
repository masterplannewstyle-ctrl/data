<?php
require_once __DIR__ . '/db.php';
// $pdo is available
$computers = $pdo->query('SELECT * FROM computers ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mon National College - IT Computer Inventory</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <h1>IT Dept — Computer Inventory</h1>
  <nav><a href="index.php">Home</a> | <a href="login.php">Admin</a></nav>
  <p class="muted">Borrow computers for classes or maintenance. Contact IT for support.</p>
</header>

<main>
  <h2>Available Computers</h2>
  <?php if (count($computers) === 0): ?>
    <p>No computers registered yet.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>Asset Tag</th><th>Model</th><th>Location</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach ($computers as $c): ?>
        <tr>
          <td><?=htmlspecialchars($c['id'])?></td>
          <td><?=htmlspecialchars($c['asset_tag'])?></td>
          <td><?=htmlspecialchars($c['model'])?><div class="specs"><?=nl2br(htmlspecialchars($c['specs']))?></div></td>
          <td><?=htmlspecialchars($c['location'])?></td>
          <td><?=htmlspecialchars($c['status'])?></td>
          <td>
            <?php if ($c['status'] === 'available'): ?>
              <a href="borrow.php?computer_id=<?=$c['id']?>">Borrow</a>
            <?php else: ?>
              Borrowed
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>

<footer>
  <p>Mon National College — IT Department</p>
</footer>
</body>
</html>
