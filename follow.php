<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$followed_id = (int)$_POST['followed_id'];
$action = $_POST['action'];

// Prevent self-following
if ($followed_id === $_SESSION['user_id']) {
    header('Location: profile.php?id=' . $followed_id);
    exit;
}

if ($action === 'follow') {
    $stmt = $pdo->prepare("INSERT INTO followers (follower_id, followed_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $followed_id]);
} elseif ($action === 'unfollow') {
    $stmt = $pdo->prepare("DELETE FROM followers WHERE follower_id = ? AND followed_id = ?");
    $stmt->execute([$_SESSION['user_id'], $followed_id]);
}

header('Location: profile.php?id=' . $followed_id);
exit;
