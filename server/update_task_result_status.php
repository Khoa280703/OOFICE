<?php
require '../database.php';

if (isset($_POST['resultId']) && isset($_POST['status'])) {
    $id = $_POST['resultId'];
    $status = $_POST['status'];

    $stmt = $conn->prepare('UPDATE taskresults SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>