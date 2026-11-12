<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$name = htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8');
$registered = isset($_GET['registered']);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>My Account | FISG</title><link rel="stylesheet" href="auth.css"></head><body><main class="auth-wrap"><a class="brand" href="index.html"><span class="brand-mark">◆</span> FISG</a><section class="auth-card"><?php if ($registered): ?><div class="message success">Your registration details have been saved successfully.</div><?php endif; ?><h1>Hello, <?= $name ?>!</h1><p class="intro">You are logged in. Your account registration details are stored in the FISG database.</p><p class="hint">This is a starter account page. It does not yet connect to live trading, deposits, withdrawals, or payment services.</p><a class="submit" style="display:block;text-align:center;text-decoration:none" href="index.html">Go to homepage</a><form method="post" action="logout.php"><button class="submit" type="submit" style="background:#47564d">Log out</button></form></section></main></body></html>
