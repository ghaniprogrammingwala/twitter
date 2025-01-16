<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$profile_id = $_GET['id']; // The profile being viewed
// Check if already following
$stmt = $pdo->prepare("SELECT * FROM followers WHERE follower_id = :follower_id AND following_id = :following_id");
$stmt->execute([
    ':follower_id' => $user_id,
    ':following_id' => $profile_id
]);
$isFollowing = $stmt->rowCount() > 0;
// Handle follow/unfollow actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['follow'])) {
        $stmt = $pdo->prepare("INSERT INTO followers (follower_id, following_id) VALUES (:follower_id, :following_id)");
        $stmt->execute([
            ':follower_id' => $user_id,
            ':following_id' => $profile_id
        ]);
        header("Location: profile.php?id=$profile_id");
        exit();
    } elseif (isset($_POST['unfollow'])) {
        $stmt = $pdo->prepare("DELETE FROM followers WHERE follower_id = :follower_id AND following_id = :following_id");
        $stmt->execute([
            ':follower_id' => $user_id,
            ':following_id' => $profile_id
        ]);
        header("Location: profile.php?id=$profile_id");
        exit();
    }
}
// Fetch profile info
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
$stmt->execute([':id' => $profile_id]);
$profile = $stmt->fetch();
if (!$profile) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($profile['username']); ?>'s Profile</title>
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

        h1 {
            font-size: 31px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #e7e9ea;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 20px;
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
        }

        button:hover {
            background-color: #1a8cd8;
            transform: scale(1.02);
        }

        .back-link {
            display: inline-block;
            color: #1d9bf0;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #1a8cd8;
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

        .container {
            animation: fadeIn 0.5s ease-out;
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
        <h1><?= htmlspecialchars($profile['username']); ?>'s Profile</h1>
        <form action="profile.php?id=<?= $profile_id; ?>" method="POST">
            <?php if ($isFollowing): ?>
                <button type="submit" name="unfollow">Unfollow</button>
            <?php else: ?>
                <button type="submit" name="follow">Follow</button>
            <?php endif; ?>
        </form>
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>
