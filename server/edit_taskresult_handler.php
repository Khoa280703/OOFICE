<?php
require '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultId = $_POST['resultId'];
    $revision = $_POST['revision'];
    $status = $_POST['status'];

    if (isset($_FILES['revisionFile']) && $_FILES['revisionFile']['error'] === UPLOAD_ERR_OK) {
        // Read the file's contents
        $revision = file_get_contents($_FILES['revisionFile']['tmp_name']);
    }

    // Update the task result in the database
    $sql = "UPDATE taskresults SET revision = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $revision, $status, $resultId);

    if ($stmt->execute()) {
        // Redirect to the task result details page if the update was successful
        header('Location: ../pages/task_result_details.php?resultId=' . $resultId);
    } else {
        // Handle error
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to the task results page if the request method is not POST
    header('Location: director.php');
}
?>