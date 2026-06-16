<?php
// Require database config which starts the session
require_once '../config/database.php';

// If already logged in, redirect to index
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        $query = "SELECT * FROM admins WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $admin = mysqli_fetch_assoc($result);
            if (password_verify($password, $admin['password'])) {
                // Regenerate session ID for security against session fixation
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                header('Location: index.php');
                exit;
            } else {
                $error_message = 'Invalid username or password.';
            }
        } else {
            $error_message = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Urban Style</title>
    <meta name="description" content="Secure administration control panel login for Urban Style Store.">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#111827',
                        accent: '#2563EB',
                        glass: 'rgba(255, 255, 255, 0.03)',
                        glassBorder: 'rgba(255, 255, 255, 0.08)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at 50% 50%, #1e293b 0%, #0f172a 100%);
        }
        .glow-sphere {
            filter: blur(120px);
            opacity: 0.15;
            pointer-events: none;
        }
    </style>
</head>
<body class="h-full flex flex-col justify-between items-center relative overflow-hidden select-none px-4 py-8">

    <!-- Glowing accent spheres for premium visual depth -->
    <div class="glow-sphere absolute -top-40 -left-40 w-96 h-96 rounded-full bg-blue-500"></div>
    <div class="glow-sphere absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-violet-600"></div>

    <!-- Main Container -->
    <div class="w-full max-w-md my-auto relative z-10">
        
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <span class="text-xs font-bold tracking-widest text-blue-400 uppercase bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20">Control Center</span>
            <h1 class="text-3xl font-extrabold text-white mt-4 tracking-tight">
                URBAN<span class="text-blue-500">STYLE</span>
            </h1>
            <p class="text-gray-400 text-sm mt-2">Sign in to manage your store, inventory, and orders</p>
        </div>

        <!-- Glassmorphic Login Card -->
        <div class="bg-glass border border-glassBorder rounded-2xl shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] backdrop-blur-md p-8 sm:p-10 transition-all duration-300 hover:shadow-blue-500/5 hover:border-white/15">
            
            <?php if (!empty($error_message)): ?>
                <!-- Alert Box -->
                <div class="bg-red-500/10 border border-red-500/20 text-red-200 text-sm rounded-lg p-4 mb-6 flex items-start space-x-2 animate-pulse" role="alert">
                    <svg class="h-5 w-5 text-red-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="login.php" method="POST" autocomplete="off">
                <div class="space-y-6">
                    
                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" name="username" id="username" required 
                                class="w-full bg-slate-900/40 text-white rounded-xl border border-white/10 pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 placeholder-gray-600 transition-all" 
                                placeholder="Enter admin username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-300">Password</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required 
                                class="w-full bg-slate-900/40 text-white rounded-xl border border-white/10 pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 placeholder-gray-600 transition-all" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" name="login" id="login-submit-btn" 
                        class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl hover:bg-blue-500 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-500/40 transition-all duration-200 mt-2 shadow-[0_4px_20px_rgba(37,99,235,0.25)] flex justify-center items-center">
                        Sign In
                    </button>

                </div>
            </form>

        </div>

    </div>

    <!-- Back to Store Link -->
    <div class="relative z-10 text-center mt-8">
        <a href="../index.php" class="text-sm text-gray-400 hover:text-white transition-colors duration-200 flex items-center justify-center space-x-1.5 group">
            <svg class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Public Store</span>
        </a>
    </div>

</body>
</html>
