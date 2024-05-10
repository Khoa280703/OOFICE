<?php
session_start(); // Start the session
require '../database.php';

// Get form data
$departmentName = $_POST['departmentName'];
$imageUrl = $_POST['imageUrl'];

// Check if department already exists
$stmt = $conn->prepare('SELECT * FROM departments WHERE departmentname = ?');
$stmt->bind_param('s', $departmentName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // Department already exists, set an error notification
  $_SESSION['notification'] = 'Department already exists';
  header('Location: ../pages/admin.php');
  exit();
}

// Insert data into database
$stmt = $conn->prepare('INSERT INTO departments (departmentname, image_url) VALUES (?, ?)');
$stmt->bind_param('ss', $departmentName, $imageUrl);

if ($stmt->execute()) {
  // Set a success notification
  $_SESSION['notification'] = 'Department added successfully';
} else {
  // Set an error notification
  $_SESSION['notification'] = 'An error occurred: ' . $stmt->error;
}

header('Location: ../pages/admin.php');
$stmt->close();
$conn->close();
?>