<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $conn = getDBConnection();
        if ($conn) {
            $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id']        = $user['id'];
                    $_SESSION['admin_username']  = $user['username'];
                    header('Location: admin.php');
                    exit;
                }
            }
            $stmt->close();
            $conn->close();
        }
        $login_error = "Username atau password salah";
    }
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - FrameKlip</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full">
            <div class="flex justify-center mb-4">
                <img src="logo.png" alt="Logo" class="h-16 w-16 object-cover rounded-full">
            </div>
            <h1 class="text-2xl font-bold text-center mb-6" style="color:#1e3a8a;">Admin Login</h1>
            <?php if (isset($login_error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Username</label>
                    <input type="text" name="username" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <button type="submit" name="login"
                    class="w-full py-3 rounded-lg font-semibold text-white"
                    style="background-color:#f97316;">
                    Login
                </button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id  = (int)$_POST['order_id'];
    $new_status = $_POST['status'];

    $conn = getDBConnection();
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header('Location: admin.php');
    exit;
}

$status_filter  = isset($_GET['status'])  ? $_GET['status']  : 'all';
$payment_filter = isset($_GET['payment']) ? $_GET['payment'] : 'all';

$conn = getDBConnection();

$where_clauses = [];
$params = [];
$types  = '';

if ($status_filter !== 'all') {
    $where_clauses[] = "o.status = ?";
    $params[] = $status_filter;
    $types   .= 's';
}
if ($payment_filter === 'verified') {
    $where_clauses[] = "p.payment_verified = 1";
} elseif ($payment_filter === 'unverified') {
    $where_clauses[] = "p.payment_verified = 0";
}

$where_sql = empty($where_clauses) ? '' : 'WHERE ' . implode(' AND ', $where_clauses);

$query = "SELECT
    o.*,
    p.total_amount,
    p.payment_verified,
    p.verified_at,
    p.verified_by,
    pr.gdrive_link,
    pr.status   AS production_status,
    pr.admin_notes,
    a.username  AS verified_by_name
    FROM orders o
    LEFT JOIN payments    p  ON o.id = p.order_id
    LEFT JOIN productions pr ON o.id = pr.order_id
    LEFT JOIN admins      a  ON p.verified_by = a.id
    $where_sql
    ORDER BY o.created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();


$stats_result = $conn->query("SELECT
    COUNT(*) as total,
    SUM(CASE WHEN o.status = 'pending'    THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN o.status = 'processing' THEN 1 ELSE 0 END) as processing,
    SUM(CASE WHEN o.status = 'completed'  THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN o.status = 'cancelled'  THEN 1 ELSE 0 END) as cancelled,
    SUM(CASE WHEN p.payment_verified = 0  THEN 1 ELSE 0 END) as unverified_payments,
    SUM(CASE WHEN p.payment_verified = 1  THEN 1 ELSE 0 END) as verified_payments
    FROM orders o
    LEFT JOIN payments p ON o.id = p.order_id");
$stats = $stats_result->fetch_assoc();

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FrameKlip</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .badge { padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600; }
        .badge-pending     { background:#fef3c7; color:#92400e; }
        .badge-processing  { background:#dbeafe; color:#1e40af; }
        .badge-completed   { background:#d1fae5; color:#065f46; }
        .badge-cancelled   { background:#fee2e2; color:#991b1b; }
        .badge-verified    { background:#d1fae5; color:#065f46; }
        .badge-unverified  { background:#fed7aa; color:#9a3412; }

        .modal {
            display:none; position:fixed; z-index:1000;
            left:0; top:0; width:100%; height:100%;
            overflow:auto; background-color:rgba(0,0,0,0.6);
        }
        .modal.active { display:flex; align-items:center; justify-content:center; }
        .modal-content {
            background:#fff; padding:30px; border-radius:15px;
            max-width:600px; width:90%; max-height:90vh; overflow-y:auto;
        }
    </style>
</head>
    <body class="bg-gray-50">

        <!-- Navbar -->
        <nav class="bg-slate-900 text-white p-4 shadow-lg">
            <div class="container mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="logo.png" alt="FrameKlip Logo" class="h-12 w-12 object-cover rounded-full">
                    <div>
                        <h1 class="text-xl font-bold">FrameKlip Admin</h1>
                        <p class="text-xs text-gray-400">Login sebagai: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                    </div>
                </div>
                <a href="?logout=1" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded font-semibold transition">Logout</a>
            </div>
        </nav>
    </body>
</html>