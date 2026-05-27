<?php
require_once __DIR__ . '/db.php';
// Show form on GET, handle on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $computer_id = $_POST['computer_id'] ?? null;
    $name = trim($_POST['borrower_name'] ?? '');
    $bid = trim($_POST['borrower_id'] ?? '');
    $purpose = trim($_POST['purpose'] ?? '');
    $due = trim($_POST['due_at'] ?? null);

    if ($computer_id && $name) {
        $ins = $pdo->prepare('INSERT INTO borrows (computer_id, borrower_name, borrower_id, purpose, borrowed_at, due_at) VALUES (?,?,?,?,?,?)');
        $ins->execute([$computer_id, $name, $bid, $purpose, date('c'), $due]);
        $upd = $pdo->prepare('UPDATE computers SET status = ? WHERE id = ?');
        $upd->execute(['borrowed', $computer_id]);
        header('Location: index.php'); exit;
    }
}

$computer = null;
if (!empty($_GET['computer_id'])) {
    $st = $pdo->prepare('SELECT * FROM computers WHERE id = ?');
    $st->execute([$_GET['computer_id']]);
    $computer = $st->fetch(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Borrow Computer</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <a href="index.php">← Back</a>
  <h2>Borrow Computer</h2>
  <?php if (!$computer): ?>
    <p>Computer not found.</p>
  <?php else: ?>
    <div class="card">
      <p><strong><?=htmlspecialchars($computer['asset_tag'])?></strong> — <?=htmlspecialchars($computer['model'])?></p>
      <form method="post">
        <input type="hidden" name="computer_id" value="<?=htmlspecialchars($computer['id'])?>">
        <label>Your name<br><input name="borrower_name" required></label>
        <label>ID (staff/student)<br><input name="borrower_id"></label>
        <label>Purpose<br><input name="purpose"></label>
        <label>Due date (YYYY-MM-DD)<br><input name="due_at" type="date"></label>
        <p><button type="submit">Confirm borrow</button></p>
      </form>
    </div>
  <?php endif; ?>
</body>
</html>
