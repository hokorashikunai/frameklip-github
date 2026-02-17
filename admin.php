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

        <div class="container mx-auto px-4 py-8">

            <!-- Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
                <div class="bg-white p-5 rounded-lg shadow text-center">
                    <h3 class="text-gray-500 text-xs font-semibold uppercase mb-1">Total</h3>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $stats['total'] ?? 0; ?></p>
                </div>
                <div class="bg-yellow-50 p-5 rounded-lg shadow text-center">
                    <h3 class="text-yellow-700 text-xs font-semibold uppercase mb-1">Pending</h3>
                    <p class="text-3xl font-bold text-yellow-800"><?php echo $stats['pending'] ?? 0; ?></p>
                </div>
                <div class="bg-blue-50 p-5 rounded-lg shadow text-center">
                    <h3 class="text-blue-700 text-xs font-semibold uppercase mb-1">Processing</h3>
                    <p class="text-3xl font-bold text-blue-800"><?php echo $stats['processing'] ?? 0; ?></p>
                </div>
                <div class="bg-green-50 p-5 rounded-lg shadow text-center">
                    <h3 class="text-green-700 text-xs font-semibold uppercase mb-1">Completed</h3>
                    <p class="text-3xl font-bold text-green-800"><?php echo $stats['completed'] ?? 0; ?></p>
                </div>
                <div class="bg-red-50 p-5 rounded-lg shadow text-center">
                    <h3 class="text-red-700 text-xs font-semibold uppercase mb-1">Cancelled</h3>
                    <p class="text-3xl font-bold text-red-800"><?php echo $stats['cancelled'] ?? 0; ?></p>
                </div>
                <div class="bg-orange-50 p-5 rounded-lg shadow border-2 border-orange-200 text-center">
                    <h3 class="text-orange-700 text-xs font-semibold uppercase mb-1">⏳ Belum Verified</h3>
                    <p class="text-3xl font-bold text-orange-800"><?php echo $stats['unverified_payments'] ?? 0; ?></p>
                </div>
                <div class="bg-emerald-50 p-5 rounded-lg shadow text-center">
                    <h3 class="text-emerald-700 text-xs font-semibold uppercase mb-1">✅ Verified</h3>
                    <p class="text-3xl font-bold text-emerald-800"><?php echo $stats['verified_payments'] ?? 0; ?></p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="font-semibold text-gray-700 text-sm">Filter Status:</span>
                    <?php
                    $status_options = ['all' => 'Semua', 'pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
                    foreach ($status_options as $val => $label):
                        $active = $status_filter === $val ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300';
                    ?>
                    <a href="?status=<?php echo $val; ?>&payment=<?php echo $payment_filter; ?>"
                    class="px-4 py-2 rounded text-sm font-medium transition <?php echo $active; ?>">
                        <?php echo $label; ?>
                    </a>
                    <?php endforeach; ?>

                    <span class="font-semibold text-gray-700 text-sm ml-4">Payment:</span>
                    <?php
                    $pay_options = ['all' => 'Semua', 'unverified' => '⏳ Belum', 'verified' => '✅ Verified'];
                    foreach ($pay_options as $val => $label):
                        $active = $payment_filter === $val ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300';
                    ?>
                    <a href="?status=<?php echo $status_filter; ?>&payment=<?php echo $val; ?>"
                    class="px-4 py-2 rounded text-sm font-medium transition <?php echo $active; ?>">
                        <?php echo $label; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Orders Table -->
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">📁 Link Video</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                    Tidak ada pesanan ditemukan
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-blue-600">#<?php echo $order['id']; ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($order['service']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($order['package']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php 
                                        $is_verified = isset($order['payment_verified']) && $order['payment_verified'] == 1;
                                        $amount = isset($order['total_amount']) ? $order['total_amount'] : 0;
                                        ?>
                                        <?php if ($is_verified): ?>
                                            <span class="badge badge-verified">✅ Verified</span>
                                            <?php if ($amount > 0): ?>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    Rp <?php echo number_format($amount, 0, ',', '.'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($order['verified_by_name'])): ?>
                                                <div class="text-xs text-gray-400 mt-1">oleh: <?php echo htmlspecialchars($order['verified_by_name']); ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-unverified">⏳ Belum</span>
                                        <?php endif; ?>
                                    </td>
                                    <!-- Kolom GDrive / Link Video -->
                                    <td class="px-6 py-4" style="max-width:180px;">
                                        <?php if (!empty($order['gdrive_link'])): ?>
                                            <a href="<?php echo htmlspecialchars($order['gdrive_link']); ?>" 
                                            target="_blank"
                                            class="inline-block bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-xs font-semibold mb-1 transition">
                                                📁 Buka Video
                                            </a>
                                            <button onclick="copyLink('<?php echo htmlspecialchars($order['gdrive_link']); ?>')"
                                                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs font-semibold transition">
                                                📋 Copy Link
                                            </button>
                                            <div class="text-xs text-gray-400 mt-1 truncate" style="max-width:160px;" title="<?php echo htmlspecialchars($order['gdrive_link']); ?>">
                                                <?php echo htmlspecialchars(substr($order['gdrive_link'], 0, 35)) . '...'; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">Belum ada link</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="badge badge-<?php echo htmlspecialchars($order['status']); ?>">
                                            <?php
                                            $status_labels = [
                                                'pending'    => '🕐 Pending',
                                                'processing' => '🔄 Processing',
                                                'completed'  => '✅ Completed',
                                                'cancelled'  => '❌ Cancelled',
                                            ];
                                            echo $status_labels[$order['status']] ?? ucfirst($order['status']);
                                            ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        <?php echo date('d/m/Y', strtotime($order['created_at'])); ?>
                                        <div><?php echo date('H:i', strtotime($order['created_at'])); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <!-- Verify / Edit button -->
                                            <button onclick="openVerifyModal(<?php echo htmlspecialchars(json_encode($order)); ?>)"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                                <?php echo (!empty($order['payment_verified']) && $order['payment_verified'] == 1) ? '📝 Edit' : '✅ Verify'; ?>
                                            </button>

                                            <!-- Complete button — muncul saat status = processing -->
                                            <?php if ($order['status'] === 'processing'): ?>
                                                <button onclick="markAsCompleted(<?php echo (int)$order['id']; ?>)"
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                                    🎬 Complete
                                                </button>
                                            <?php endif; ?>

                                            <!-- Cancel button — muncul saat status = pending atau processing -->
                                            <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                                                <button onclick="cancelOrder(<?php echo (int)$order['id']; ?>)"
                                                    class="bg-red-400 hover:bg-red-500 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                                    ❌ Cancel
                                                </button>
                                            <?php endif; ?>

                                            <!-- GDrive sudah ada di kolom tersendiri -->

                                            <!-- WhatsApp -->
                                            <a href="https://wa.me/<?php
                                                    $phone = preg_replace('/[^0-9]/', '', $order['customer_phone']);
                                                    if (substr($phone, 0, 1) === '0') $phone = '62' . substr($phone, 1);
                                                    echo $phone;
                                                ?>" target="_blank"
                                                class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded text-xs font-semibold text-center transition">
                                                📱 WA
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div><!-- end container -->

    </body>
</html>