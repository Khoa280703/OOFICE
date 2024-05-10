<?php
require '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['taskid'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    if (isset($_FILES['descriptionFile']) && $_FILES['descriptionFile']['error'] === UPLOAD_ERR_OK) {
        // Read the file's contents
        $description = file_get_contents($_FILES['descriptionFile']['tmp_name']);
    }
    $responder = $_POST['responder'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    // Update the task in the database
    $sql = "UPDATE tasks SET title = ?, description = ?, responder = ?, deadline = ?, status = ? WHERE taskid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssissi', $title, $description, $responder, $deadline, $status, $taskId);

    if ($stmt->execute()) {
        // Redirect to the task details page if the update was successful
        header('Location: ../pages/task_details.php?taskId=' . $taskId);
    } else {
        // Handle error
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to the tasks page if the request method is not POST
    header('Location: director.php');
}