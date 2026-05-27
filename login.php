<?php
require_once __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $st = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $st->execute([$user]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['user'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        header('Location: admin.php'); exit;
    }
    $error = 'Invalid credentials';
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <a href="index.php">← Back</a>
  <h2>Admin Login</h2>
  <?php if (!empty($error)): ?><p class="error"><?=htmlspecialchars($error)?></p><?php endif;?>
  <form method="post">
    <label>Username<br><input name="username" required></label>
    <label>Password<br><input name="password" type="password" required></label>
    <p><button type="submit">Login</button></p>
  </form>
  <p>Default admin: <strong>admin</strong>/<strong>admin123</strong></p>
</body>
</html>
