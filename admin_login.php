<?php
session_start();
require __DIR__ . '/db.php';
if (!empty($_SESSION['admin_id']) && ($_SESSION['admin_role'] ?? '') === 'admin') { header('Location: admin.php'); exit; }
$error = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? '')); $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') $error = 'Please enter your admin email and password.';
    else {
        try {
            $s = $pdo->prepare('SELECT id,full_name,email,password_hash,role FROM users WHERE email=? LIMIT 1');
            $s->execute([$email]); $u = $s->fetch();
            if ($u && $u['role'] === 'admin' && password_verify($password, $u['password_hash'])) {
                session_regenerate_id(true); $_SESSION['admin_id']=(int)$u['id']; $_SESSION['admin_name']=$u['full_name']; $_SESSION['admin_role']='admin';
                header('Location: admin.php'); exit;
            }
            $error = 'Invalid admin credentials, or this account does not have admin access.';
        } catch (PDOException $e) { $error = 'Admin setup is incomplete. Run admin_setup.sql in phpMyAdmin first.'; }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login | FISG</title><link rel="stylesheet" href="auth.css"></head>
<body><main class="auth-wrap"><a class="brand" href="index.html"><span class="brand-mark">◆</span> FISG <span style="font-size:12px;color:#68766d">ADMIN</span></a><section class="auth-card"><h1>Admin sign in</h1><p class="intro">Sign in with an account assigned the administrator role.</p>
<?php if ($error): ?><div class="message error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form method="post" action="admin_login.php"><div class="field"><label for="email">Admin email</label><input id="email" name="email" type="email" required maxlength="190" autocomplete="username" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="field"><label for="password">Password</label><input id="password" name="password" type="password" required autocomplete="current-password"></div><button class="submit" type="submit">Log in to dashboard</button></form><p class="foot">Authorised administrators only.</p></section></main></body></html>
