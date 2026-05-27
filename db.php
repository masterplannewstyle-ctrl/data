<?php
// Simple SQLite connection + initialization
session_start();
$dir = __DIR__ . '/data';
if (!is_dir($dir)) mkdir($dir, 0755, true);
$dbFile = $dir . '/database.sqlite';
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables if they don't exist
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT UNIQUE,
  password TEXT,
  role TEXT
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS computers (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  asset_tag TEXT UNIQUE,
  model TEXT,
  specs TEXT,
  location TEXT,
  status TEXT DEFAULT 'available'
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS borrows (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  computer_id INTEGER,
  borrower_name TEXT,
  borrower_id TEXT,
  purpose TEXT,
  borrowed_at TEXT,
  due_at TEXT,
  returned_at TEXT,
  notes TEXT,
  FOREIGN KEY(computer_id) REFERENCES computers(id)
)");

// Ensure a default admin exists (username: admin, password: admin123)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$stmt->execute(['admin']);
if ($stmt->fetchColumn() == 0) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $ins = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)");
    $ins->execute(['admin', $hash, 'admin']);
}

// expose $pdo to including scripts
// use: require 'db.php'; global $pdo;
?>
