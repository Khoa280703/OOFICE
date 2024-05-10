<?php
session_start(); // Start the session
require '../database.php';

// Get form data
$departmentName = $_POST['departmentName'];

// Delete department from database
$stmt = $conn->prepare('DELETE FROM departments WHERE departmentname = ?');
$stmt->bind_param('s', $departmentName);

if ($stmt->execute()) {
  // Set a success notification
  $_SESSION['notification'] = 'Department deleted successfully';
} else {
  // Set an error notification
  $_SESSION['notification'] = 'An error occurred: ' . $stmt->error;
}

header('Location: ../pages/admin.php');
$stmt->close();
$conn->close();
?>