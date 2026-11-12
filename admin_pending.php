<?php
session_start();
$name = htmlspecialchars($_SESSION['pending_admin_name'] ?? 'Applicant', ENT_QUOTES, 'UTF-8');
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Request Pending | FISG</title><link rel="stylesheet" href="auth.css"></head>
<body><main class="auth-wrap"><a class="brand" href="index.html"><span class="brand-mark">◆</span> FISG</a><section class="auth-card"><div class="message success">Admin request submitted</div><h1>Thank you, <?= $name ?>!</h1><p class="intro">Your account has been created, and your request for admin access is waiting for approval.</p><p class="hint">For security, selecting Admin during registration does not automatically grant access to registered users' personal data. An existing administrator must approve your request first.</p><a class="submit" style="display:block;text-align:center;text-decoration:none" href="login.php">Go to login</a></section><a class="back" href="index.html">← Back to FISG homepage</a></main></body></html>
