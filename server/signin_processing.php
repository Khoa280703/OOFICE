<?php
require '../database.php';
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $stmt = $conn->prepare('SELECT rolename FROM roles WHERE roleid = ?');
    $stmt->bind_param('i', $user['roleid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $role = $result->fetch_assoc();

    $_SESSION['user'] = $user; // Store the user data in a session variable
    header('Location: ../pages/' . strtolower($role['rolename']) . '.php');
    exit;
} else {
    // Invalid credentials
    $_SESSION['message'] = 'Invalid username or password';
    header('Location: ../pages/signin.php');
    exit;
}
?>