<?php
require '../database.php';

if (isset($_POST['taskId'])) {
    $id = $_POST['taskId'];

    $stmt = $conn->prepare('DELETE FROM tasks WHERE taskid = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>