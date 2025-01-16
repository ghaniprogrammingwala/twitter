<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tweet'])) {
    $tweet = trim($_POST['tweet']);
    if (!empty($tweet)) {
        $stmt = $pdo->prepare("INSERT INTO tweets (user_id, content, created_at) VALUES (:user_id, :content, NOW())");
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':content' => $tweet
        ]);
        $message = "Tweet posted successfully!";
    } else {
        $message = "Tweet cannot be empty.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Tweet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #000000;
            color: #e7e9ea;
            min-height: 100vh;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            min-height: 100vh;
            border-left: 1px solid #2f3336;
            border-right: 1px solid #2f3336;
        }

        .header {
            padding: 16px;
            border-bottom: 1px solid #2f3336;
            position: sticky;
            top: 0;
            background-color: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(12px);
            z-index: 1000;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
        }

        .form-container {
            padding: 16px;
            border-bottom: 1px solid #2f3336;
        }

        .message {
            color: #1d9bf0;
            margin-bottom: 12px;
            text-align: center;
            font-size: 15px;
        }

        .tweet-input {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background-color: transparent;
            border: none;
            color: #e7e9ea;
            resize: none;
            outline: none;
            margin-bottom: 12px;
            height: 120px;
        }

        .tweet-input::placeholder {
            color: #71767b;
        }

        .tweet-button {
            background-color: #1d9bf0;
            color: #ffffff;
            border: none;
            border-radius: 9999px;
            padding: 8px 16px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s ease;
            float: right;
        }

        .tweet-button:hover {
            background-color: #1a8cd8;
        }

        .back-link {
            text-align: center;
            padding: 16px;
            border-top: 1px solid #2f3336;
        }

        .back-link a {
            color: #1d9bf0;
            text-decoration: none;
            font-size: 15px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                width: 100%;
                border: none;
            }
            
            .header {
                padding: 12px 16px;
            }
            
            .form-container {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Post Tweet</h1>
        </div>
        <div class="form-container">
            <?php if (isset($message)): ?>
                <div class="message"><?= htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form action="post_tweet.php" method="POST">
                <textarea 
                    name="tweet" 
                    placeholder="What's happening?" 
                    maxlength="160" 
                    required 
                    class="tweet-input"></textarea>
                <button type="submit" class="tweet-button">Tweet</button>
            </form>
        </div>
        <div class="back-link">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>
