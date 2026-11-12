
<?php
session_start();

if (
    empty($_SESSION['admin_id']) ||
    ($_SESSION['admin_role'] ?? '') !== 'admin'
) {
    header('Location: admin_login.php');
    exit;
}

require __DIR__ . '/db.php';

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Search and pagination
$q = trim($_GET['q'] ?? '');

$page = max(1, (int)($_GET['page'] ?? 1));

$perPage = 10;
$offset = ($page - 1) * $perPage;

$where = " WHERE role = 'user'";
$params = [];

if ($q !== '') {
    $where .= " AND (
        full_name LIKE ?
        OR email LIKE ?
        OR phone LIKE ?
        OR state LIKE ?
    )";

    $needle = '%' . $q . '%';

    $params = [
        $needle,
        $needle,
        $needle,
        $needle
    ];
}

try {

    // Total registered users
    $s = $pdo->prepare(
        'SELECT COUNT(*) FROM users' . $where
    );

    $s->execute($params);

    $total = (int)$s->fetchColumn();

    $pages = max(
        1,
        (int)ceil($total / $perPage)
    );

    // Export CSV
    if (($_GET['export'] ?? '') === 'csv') {

        $s = $pdo->prepare(
            'SELECT id, full_name, email, phone, state, created_at
             FROM users' . $where . '
             ORDER BY created_at DESC'
        );

        $s->execute($params);

        header('Content-Type: text/csv; charset=utf-8');

        header(
            'Content-Disposition: attachment; filename="fisg_registered_users.csv"'
        );

        $out = fopen('php://output', 'w');

        // UTF-8 BOM for Excel
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, [
            'User ID',
            'Full Name',
            'Email',
            'Phone',
            'State',
            'Registered At'
        ]);

        while ($r = $s->fetch(PDO::FETCH_ASSOC)) {

            fputcsv($out, [
                $r['id'],
                $r['full_name'],
                $r['email'],
                $r['phone'],
                $r['state'],
                $r['created_at']
            ]);
        }

        fclose($out);
        exit;
    }

    // Get users for current page
    $s = $pdo->prepare(
        'SELECT id, full_name, email, phone, state, created_at
         FROM users' . $where . '
         ORDER BY created_at DESC
         LIMIT ' . $perPage . '
         OFFSET ' . $offset
    );

    $s->execute($params);

    $users = $s->fetchAll(PDO::FETCH_ASSOC);

    // Registered today (user accounts only)
    $today = (int)$pdo->query(
        "SELECT COUNT(*)
         FROM users
         WHERE role = 'user'
         AND DATE(created_at) = CURDATE()"
    )->fetchColumn();

} catch (PDOException $e) {

    http_response_code(500);

    exit(
        'Admin setup incomplete. Please check your database table and columns, including state.'
    );
}

// Pagination URL
function urlq($changes = []) {

    $a = array_merge($_GET, $changes);

    unset($a['export']);

    return '?' . http_build_query($a);
}

$name = e($_SESSION['admin_name'] ?? 'Administrator');

?>

<!doctype html>
<html lang="en">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>FISG Admin Dashboard</title>

<link rel="stylesheet" href="auth.css">

<style>

/* GENERAL */

body {
    display: block;
    padding: 0;
    background: #f4f7f5;
}

/* TOP NAVIGATION */

.top {
    background: #fff;
    border-bottom: 1px solid #e1e9e3;
    padding: 16px max(20px, calc((100vw - 1180px)/2));

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.top .brand {
    margin: 0;
    text-align: left;
    font-size: 22px;
}

.top a {
    color: #08763e;
    text-decoration: none;
    font-weight: 700;
    font-size: 12px;
}

/* MAIN CONTAINER */

.wrap {
    max-width: 1180px;
    margin: 28px auto;
    padding: 0 20px;
}

/* PAGE HEADER */

.head {
    display: flex;
    justify-content: space-between;
    align-items: end;
    gap: 18px;
    flex-wrap: wrap;
}

.head h1 {
    font-size: 28px;
    margin: 0 0 7px;
}

.muted {
    color: #68766d;
    font-size: 13px;
}

/* STATISTICS */

.stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin: 24px 0;
}

.stat,
.panel {
    background: #fff;
    border: 1px solid #e1e9e3;
    border-radius: 12px;
}

.stat {
    padding: 20px;
}

.stat small {
    color: #68766d;
}

.stat strong {
    display: block;
    font-size: 28px;
    margin-top: 8px;
}

/* PANEL */

.panel {
    overflow: hidden;
}

.ph {
    padding: 18px 20px;
    border-bottom: 1px solid #edf1ee;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.ph h2 {
    font-size: 16px;
    margin: 0;
}

/* SEARCH */

.search {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.search input {
    border: 1px solid #d8e3dc;
    border-radius: 7px;
    padding: 10px;
    min-width: 220px;
    font-family: inherit;
    font-size: 12px;
}

/* BUTTONS */

.btn {
    display: inline-block;
    border: 0;
    border-radius: 7px;
    padding: 10px 13px;

    background: #08763e;
    color: white !important;

    text-decoration: none;
    font-weight: 700;
    font-size: 12px;
    cursor: pointer;
}

.btn.light {
    background: #edf5ef;
    color: #08763e !important;
}

/* TABLE */

.scroll {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
}

th,
td {
    text-align: left;
    padding: 13px 16px;
    border-bottom: 1px solid #edf1ee;
    font-size: 12px;
}

th {
    background: #f9fbf9;
    color: #65736a;
    font-size: 11px;
    text-transform: uppercase;
}

tbody tr:last-child td {
    border: 0;
}

tbody tr:hover {
    background: #f8fbf8;
}

.empty {
    text-align: center;
    color: #68766d;
    padding: 25px;
}

/* PAGINATION */

.pag {
    padding: 16px 20px;

    display: flex;
    justify-content: space-between;

    font-size: 12px;
    color: #68766d;
}

.pag a {
    color: #08763e;
    text-decoration: none;
    font-weight: 700;
}

/* PRIVACY NOTICE */

.note {
    margin: 14px 0;
    background: #fff8e6;
    border: 1px solid #f0dfb2;
    border-radius: 8px;
    padding: 12px;
    font-size: 12px;
    color: #735a16;
}

/* RESPONSIVE */

@media(max-width: 650px) {

    .stats {
        grid-template-columns: 1fr;
    }

    .head h1 {
        font-size: 24px;
    }

    .top {
        padding: 14px 18px;
    }

    .search input {
        min-width: 100%;
    }

}

</style>

</head>

<body>

<!-- TOP NAVIGATION -->

<header class="top">

    <a class="brand" href="index.html">

        <span class="brand-mark">◆</span>

        FISG

        <span style="font-size:11px;color:#68766d">
            ADMIN
        </span>

    </a>

    <span>
        Signed in as <?= $name ?>
        ·
        <a href="admin_logout.php">Log out</a>
    </span>

</header>


<main class="wrap">

    <!-- PAGE HEADER -->

    <div class="head">

        <div>

            <h1>Registered Users</h1>

            <p class="muted">
                View, search and export registered accounts.
            </p>

        </div>

        <a class="btn" href="<?= e(urlq()) ?>&export=csv">
            Export CSV
        </a>

    </div>


    <!-- STATISTICS -->

    <div class="stats">

        <section class="stat">

            <small>Total registered users</small>

            <strong>
                <?= number_format($total) ?>
            </strong>

        </section>


        <section class="stat">

            <small>Registered today</small>

            <strong>
                <?= number_format($today) ?>
            </strong>

        </section>


        <section class="stat">

            <small>Records on this page</small>

            <strong>
                <?= number_format(min($perPage, max(0, $total - $offset))) ?>
            </strong>

        </section>

    </div>


    <!-- PRIVACY NOTICE -->

    <div class="note">

        Privacy reminder: use this dashboard only for authorised
        business purposes. Passwords are never shown or exported.

    </div>


    <!-- USER RECORDS -->

    <section class="panel">

        <div class="ph">

            <h2>User records</h2>

            <form class="search" method="get">

                <input
                    type="search"
                    name="q"
                    placeholder="Search name, email, phone or state"
                    value="<?= e($q) ?>"
                >

                <button class="btn" type="submit">
                    Search
                </button>

                <?php if ($q !== ''): ?>

                    <a class="btn light" href="admin.php">
                        Clear
                    </a>

                <?php endif; ?>

            </form>

        </div>


        <!-- TABLE -->

        <div class="scroll">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Full name</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>State</th>

                        <th>Registration date</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (!$users): ?>

                        <tr>

                            <td colspan="6" class="empty">

                                No registered users found.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($users as $u): ?>

                            <tr>

                                <td>
                                    <?= (int)$u['id'] ?>
                                </td>

                                <td>
                                    <?= e($u['full_name']) ?>
                                </td>

                                <td>
                                    <?= e($u['email']) ?>
                                </td>

                                <td>
                                    <?= e($u['phone']) ?>
                                </td>

                                <td>
                                    <?= e($u['state'] ?: '-') ?>
                                </td>

                                <td>
                                    <?= e($u['created_at']) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        <!-- PAGINATION -->

        <div class="pag">

            <span>
                Page <?= min($page, $pages) ?>
                of <?= $pages ?>
            </span>

            <span>

                <?php if ($page > 1): ?>

                    <a href="<?= e(urlq(['page' => $page - 1])) ?>">
                        ← Previous
                    </a>

                <?php endif; ?>


                <?php if ($page < $pages): ?>

                    <a href="<?= e(urlq(['page' => $page + 1])) ?>">
                        Next →
                    </a>

                <?php endif; ?>

            </span>

        </div>

    </section>


    <p class="muted">

        FISG Admin Dashboard · Restricted to authorised administrator accounts.

    </p>

</main>

</body>
</html>
