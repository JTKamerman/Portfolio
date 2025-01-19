<?php
session_start();

// Simple credentials (demo only!)
$validUsername = 'admin';
$validPassword = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Ptero-Style Mobile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Mobile Header -->
<div class="mobile-header">
  <span class="brand">My Panel</span>
  <div>
    <button id="mobileMenuBtn" class="mobile-menu-btn">Menu</button>
    <button id="mobileThemeBtn" class="mobile-theme-btn">Theme</button>
  </div>
</div>

<!-- Sidebar -->
<div class="sidebar hidden-mobile" id="sidebar">
  <div class="sidebar-header">
    <h1>My Panel</h1>
  </div>
  <nav>
    <?php
    // If you want to load menuLinks even on the login page, do so:
    $jsonData = file_get_contents(__DIR__ . '/content.json');
    $d = json_decode($jsonData, true) ?? [];
    if (!empty($d['menuLinks'])) {
      foreach ($d['menuLinks'] as $link) {
        echo '<a href="'.htmlspecialchars($link['url']).'">'.htmlspecialchars($link['label']).'</a>';
      }
    }
    ?>
    <button id="themeToggle" class="btn">Toggle Theme</button>
  </nav>
  <div class="sidebar-footer">
    <p>&copy; <?php echo date('Y'); ?></p>
  </div>
</div>

<div class="login-container">
  <div class="login-panel">
    <h2>Admin Login</h2>
    <?php if (isset($error)): ?>
      <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="Enter username" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>
      </div>
      <button class="btn" type="submit">Login</button>
    </form>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>
