<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Twitter</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #000000;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: #000000;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            border: 1px solid #333333;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1d9bf0 0%, #1e98e9 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .container:hover::before {
            opacity: 1;
        }

        .logo {
            width: 40px;
            height: 40px;
            margin-bottom: 20px;
            fill: #1d9bf0;
        }

        h2 {
            font-size: 31px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #e7e9ea;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .input-group {
            position: relative;
            transition: transform 0.2s ease;
        }

        .input-group:hover {
            transform: scale(1.02);
        }

        input {
            width: 100%;
            padding: 16px;
            background-color: transparent;
            border: 1px solid #333333;
            border-radius: 8px;
            color: #e7e9ea;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #1d9bf0;
            outline: none;
            box-shadow: 0 0 0 2px rgba(29, 155, 240, 0.2);
        }

        input::placeholder {
            color: #71767b;
        }

        button {
            background-color: #1d9bf0;
            color: #ffffff;
            border: none;
            border-radius: 9999px;
            padding: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #1a8cd8;
            transform: scale(1.02);
        }

        .error {
            color: #f4212e;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
                border-radius: 0;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <svg viewBox="0 0 24 24" class="logo">
            <path d="M23.643 4.937c-.835.37-1.732.62-2.675.733.962-.576 1.7-1.49 2.048-2.578-.9.534-1.897.922-2.958 1.13-.85-.904-2.06-1.47-3.4-1.47-2.572 0-4.658 2.086-4.658 4.66 0 .364.042.718.12 1.06-3.873-.195-7.304-2.05-9.602-4.868-.4.69-.63 1.49-.63 2.342 0 1.616.823 3.043 2.072 3.878-.764-.025-1.482-.234-2.11-.583v.06c0 2.257 1.605 4.14 3.737 4.568-.392.106-.803.162-1.227.162-.3 0-.593-.028-.877-.082.593 1.85 2.313 3.198 4.352 3.234-1.595 1.25-3.604 1.995-5.786 1.995-.376 0-.747-.022-1.112-.065 2.062 1.323 4.51 2.093 7.14 2.093 8.57 0 13.255-7.098 13.255-13.254 0-.2-.005-.402-.014-.602.91-.658 1.7-1.477 2.323-2.41z" />
        </svg>
        <h2>Sign in to Twitter</h2>
        <form method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required />
            </div>
            <button type="submit">Log in</button>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
