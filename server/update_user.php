<?php
session_start(); // Start the session
require '../database.php';

// Get form data
$id = $_POST['id'];

// Fetch current values from the database
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('s', $id);
$stmt->execute();
$current_user = $stmt->get_result()->fetch_assoc();

// If the new values are empty, use the current values
$name = empty($_POST['name']) ? $current_user['name'] : $_POST['name'];
$username = empty($_POST['username']) ? $current_user['username'] : $_POST['username'];
$password = empty($_POST['password']) ? $current_user['password'] : password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the new password if it's not empty
$role_id = empty($_POST['roleid']) ? $current_user['roleid'] : $_POST['roleid'];
$department_id = empty($_POST['departmentid']) ? $current_user['departmentid'] : $_POST['departmentid'];

// Update data in database
$stmt = $conn->prepare('UPDATE users SET name = ?, username = ?, password = ?, roleid = ?, departmentid = ? WHERE id = ?');
$stmt->bind_param('ssssss', $name, $username, $password, $role_id, $department_id, $id);

if ($stmt->execute()) {
  // Set a success notification
  $_SESSION['notification'] = 'User updated successfully';
  $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $updated_user = $stmt->get_result()->fetch_assoc();

  // Update the user data in the session
  $_SESSION['user'] = $updated_user;
} else {
  // Set an error notification
  $_SESSION['notification'] = 'An error occurred: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>