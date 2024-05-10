<?php
require '../database.php';
session_start();

if (!isset($_SESSION['user'])) {
    echo "You are not logged in!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $newName = $_POST['newName'];
    $newUsername = $_POST['newUsername'];
    $newPassword = $_POST['newPassword'];

    // Get the current user details
    $stmt = $conn->prepare('SELECT name, username, password FROM users WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If the new values are empty, keep the old values
    $newName = empty($newName) ? $user['name'] : $newName;
    $newUsername = empty($newUsername) ? $user['username'] : $newUsername;
    $newPassword = empty($newPassword) ? $user['password'] : $newPassword;

    $stmt = $conn->prepare('UPDATE users SET name = ?, username = ?, password = ? WHERE id = ?');
    $stmt->bind_param('sssi', $newName, $newUsername, $newPassword, $id);
    $stmt->execute();
    $_SESSION['user']['name'] = $newName;
    $_SESSION['user']['username'] = $newUsername;

    echo "User details updated successfully!";
    header('Location: ../pages/admin.php');
} else {
    echo "Invalid request!";
    header('Location: ../pages/admin.php');
}
?>