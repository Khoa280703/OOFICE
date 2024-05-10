<?php
session_start(); // Start the session
require '../database.php';

// Get form data
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$role_id = $_POST['role']; // Get the role ID
$department_id = $_POST['department']; // Get the department ID

// Check if username already exists
$stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // Username already exists
  $_SESSION['notification'] = 'Username already exists';
  header('Location: ../pages/admin.php');
  exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert data into database
$stmt = $conn->prepare('INSERT INTO users (name, username, password, roleid, departmentid) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('sssss', $name, $username, $hashed_password, $role_id, $department_id);

if ($stmt->execute()) {
  // Set a success notification
  $_SESSION['notification'] = 'User added successfully';
} else {
  // Set an error notification
  $_SESSION['notification'] = 'An error occurred: ' . $stmt->error;
}

header('Location: ../pages/admin.php');
$stmt->close();
$conn->close();
?>