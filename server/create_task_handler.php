<?php
session_start();
require '../database.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    if (isset($_FILES['descriptionFile']) && $_FILES['descriptionFile']['error'] === UPLOAD_ERR_OK) {
        // Read the file's contents
        $description = file_get_contents($_FILES['descriptionFile']['tmp_name']);
    }
    $deadline = $_POST['deadline'];
    $creator = $_SESSION['user']['id']; // Get the ID of the currently logged-in user
    $responder = $_POST['responder'];
    $status = 'Not accepted'; // Default status

    // Prepare an SQL statement
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, deadline, creator, responder, status) VALUES (?, ?, ?, ?, ?, ?)");

    // Bind the variables to the statement as parameters
    $stmt->bind_param("ssssss", $title, $description, $deadline, $creator, $responder, $status);

    // Execute the statement
    if ($stmt->execute()) {
        // Fetch the role of the currently logged-in user
        $sql = "SELECT roleid FROM users WHERE id = $creator";
        $result = $conn->query($sql);
        $userRole = $result->fetch_assoc()['roleid'];

        // Determine the page to redirect to based on the user's role
        switch ($userRole) {
            case 2:
                $page = '../pages/director.php';
                break;
            case 3:
                $page = '../pages/head.php';
                break;
            default:
                $page = '../index.html';
        }

        // Redirect to the determined page
        header("Location: $page");
        exit;
    } else {
        // Display an error message if the task could not be created
        echo "Error: " . $stmt->error;

    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>