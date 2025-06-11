<?php
session_start();

// If already logged in, redirect to home
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

// Initialize error message
$error = '';

// Process login or signup submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        // For demo purposes, users are stored in session variable 'users' array
        if (!isset($_SESSION['users'])) {
            $_SESSION['users'] = [];
        }
        $users = &$_SESSION['users'];

        if ($action === 'signup') {
            if (isset($users[$username])) {
                $error = 'Username already exists. Please choose another.';
            } else {
                // Store user with hashed password
                $users[$username] = password_hash($password, PASSWORD_DEFAULT);
                $_SESSION['user'] = $username;
                $_SESSION['cart'] = [];
                header('Location: home.php');
                exit;
            }
        } elseif ($action === 'login') {
            if (!isset($users[$username]) || !password_verify($password, $users[$username])) {
                $error = 'Invalid username or password.';
            } else {
                $_SESSION['user'] = $username;
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                header('Location: home.php');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login / Signup - ElectroBay</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f3f4f6;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #374151;
    }
    .form-container {
      background: white;
      padding: 32px 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      max-width: 400px;
      width: 90%;
      text-align: center;
    }
    h2 {
      margin-bottom: 24px;
      font-weight: 700;
      color: #1e293b;
    }
    form {
      margin-bottom: 16px;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px 16px;
      margin-bottom: 20px;
      border: 1.5px solid #cbd5e1;
      border-radius: 12px;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }
    input[type="text"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #2563eb;
    }
    .btn {
      background-color: #2563eb;
      color: #fff;
      border: none;
      padding: 14px 20px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
    }
    .btn:hover {
      background-color: #1e40af;
    }
    .error-message {
      margin-bottom: 20px;
      color: #dc2626;
      font-weight: 600;
    }
    .toggle-action {
      font-size: 14px;
      color: #2563eb;
      cursor: pointer;
      user-select: none;
      margin-top: 12px;
      display: inline-block;
      text-decoration: underline;
    }
    .material-icons {
      vertical-align: middle;
      font-size: 20px;
    }
  </style>
</head>
<body>
  <div class="form-container" role="main">
    <h2 id="form-title">Login to ElectroBay</h2>
    <?php if ($error !== ''): ?>
      <div class="error-message" role="alert"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form id="auth-form" method="POST" novalidate>
      <input type="text" name="username" autocomplete="username" placeholder="Username" required aria-label="Username" />
      <input type="password" name="password" autocomplete="current-password" placeholder="Password" required aria-label="Password" />
      <input type="hidden" name="action" value="login" />
      <button type="submit" class="btn" aria-live="polite">
        <span class="material-icons" aria-hidden="true">login</span> Login
      </button>
    </form>
    <div>
      <span id="toggle-text">Don't have an account?</span>
      <button id="toggle-action-btn" class="toggle-action" aria-controls="auth-form" aria-expanded="false">Sign up here</button>
    </div>
  </div>

  <script>
    const toggleBtn = document.getElementById('toggle-action-btn');
    const formTitle = document.getElementById('form-title');
    const authForm = document.getElementById('auth-form');
    const toggleText = document.getElementById('toggle-text');
    let isLogin = true;

    toggleBtn.addEventListener('click', () => {
      isLogin = !isLogin;
      if (isLogin) {
        formTitle.textContent = 'Login to ElectroBay';
        authForm.querySelector('button').innerHTML = '<span class="material-icons" aria-hidden="true">login</span> Login';
        toggleText.textContent = "Don't have an account?";
        toggleBtn.textContent = 'Sign up here';
        authForm.action.value = 'login';
        authForm.querySelector('input[name="password"]').setAttribute('autocomplete', 'current-password');
      } else {
        formTitle.textContent = 'Sign up for ElectroBay';
        authForm.querySelector('button').innerHTML = '<span class="material-icons" aria-hidden="true">person_add</span> Sign Up';
        toggleText.textContent = "Already have an account?";
        toggleBtn.textContent = 'Login here';
        authForm.action.value = 'signup';
        authForm.querySelector('input[name="password"]').setAttribute('autocomplete', 'new-password');
      }
      toggleBtn.setAttribute('aria-expanded', !isLogin);
      authForm.reset();
    });
  </script>
</body>
</html>

