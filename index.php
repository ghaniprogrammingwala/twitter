<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Fetch tweets with user information
$tweets = $pdo->query("
    SELECT tweets.*, users.username 
    FROM tweets 
    JOIN users ON tweets.user_id = users.id 
    ORDER BY tweets.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter Home</title>
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

        .timeline h2 {
            padding: 16px;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid #2f3336;
        }

        .tweet {
            padding: 16px;
            border-bottom: 1px solid #2f3336;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }

        .tweet:hover {
            background-color: rgba(255, 255, 255, 0.03);
        }

        .tweet-header {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
        }

        .tweet-header a {
            text-decoration: none;
            color: #e7e9ea;
            font-weight: 700;
            margin-right: 4px;
        }

        .tweet-header a:hover {
            text-decoration: underline;
        }

        .tweet-username {
            color: #71767b;
            font-size: 15px;
        }

        .tweet-content {
            font-size: 15px;
            line-height: 1.5;
            margin: 4px 0;
        }

        .tweet-timestamp {
            color: #71767b;
            font-size: 14px;
            margin-top: 4px;
        }

        /* Animations */
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

        .tweet {
            animation: fadeIn 0.3s ease-out;
        }

        /* Mobile Responsiveness */
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
            <h1>Home</h1>
        </div>
        <div class="form-container">
            <form method="POST" action="post_tweet.php">
                <input type="text" 
                       name="content" 
                       placeholder="What's happening?" 
                       maxlength="160" 
                       required 
                       class="tweet-input">
                <button type="submit" class="tweet-button">Tweet</button>
            </form>
        </div>
        <div class="timeline">
            <?php foreach ($tweets as $tweet): ?>
                <div class="tweet">
                    <div class="tweet-header">
                        <a href="profile.php?id=<?= $tweet['user_id'] ?>">
                            @<?= htmlspecialchars($tweet['username']) ?>
                        </a>
                        <span class="tweet-username">·</span>
                    </div>
                    <div class="tweet-content">
                        <?= htmlspecialchars($tweet['content']) ?>
                    </div>
                    <div class="tweet-timestamp">
                        <?= date('M j', strtotime($tweet['created_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
