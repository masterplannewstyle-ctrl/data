<?php
require_once __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = $_POST['borrow_id'] ?? null;
    if ($borrow_id) {
        $st = $pdo->prepare('SELECT computer_id FROM borrows WHERE id = ?');
        $st->execute([$borrow_id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $pdo->prepare('UPDATE borrows SET returned_at = ? WHERE id = ?')->execute([date('c'), $borrow_id]);
            $pdo->prepare('UPDATE computers SET status = ? WHERE id = ?')->execute(['available', $row['computer_id']]);
        }
    }
}
header('Location: admin.php'); exit;
