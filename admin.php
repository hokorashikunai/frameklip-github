<?php
session_start();
require_once 'config.php';

// Simple authentication
if (!isset($_SESSION['admin_logged_in'])) {
    // Check if login form submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                header('Location: admin.php');
                exit;
            }
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
    <body class="bg-gray-100">
        <div class="min-h-screen flex items-center justify-center">
            <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full">
                <h1 class="text-2xl font-bold text-center mb-6" style="color: #1e3a8a;">Admin Login</h1>
                <?php if (isset($login_error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $login_error; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Username</label>
                        <input type="text" name="username" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                    </div>
                    <button type="submit" name="login" class="w-full py-3 rounded-lg font-semibold text-white" style="background-color: #f97316;">
                        Login
                    </button>
                </form>
                <p class="text-sm text-gray-500 mt-4 text-center">
                    Default: admin / admin123
                </p>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
