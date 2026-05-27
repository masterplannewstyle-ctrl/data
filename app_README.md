# IT Dept Computer Management — Minimal PHP App

This is a small demo application to manage computer inventory and borrowing for the IT Department at Mon National College. It uses PHP and SQLite (no external DB required).

Quick start

1. Open a terminal and change to the project folder:

```bash
cd /workspaces/data
```

2. Start PHP built-in server:

```bash
php -S localhost:8000
```

3. Open http://localhost:8000/index.php in your browser.

Default admin credentials

- username: admin
- password: admin123

Notes
- The SQLite database file is created at `data/database.sqlite` on first run.
- This app is intentionally minimal: for production use, add CSRF protection, input validation, stronger auth, and backups.
