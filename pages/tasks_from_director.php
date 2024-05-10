<?php
$limit = 6; // Number of tasks per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$responderid = $_SESSION['user']['id'];

$result = $conn->query('SELECT COUNT(*) AS total FROM tasks WHERE responder = '.$responderid);
$totalTasks = $result->fetch_assoc()['total'];
$totalPages = ceil($totalTasks / $limit);

$result = $conn->query("
    SELECT tasks.*, creatorUser.name AS creatorName, responderUser.name AS responderName
    FROM tasks
    INNER JOIN users AS creatorUser ON tasks.creator = creatorUser.id
    INNER JOIN users AS responderUser ON tasks.responder = responderUser.id
    WHERE tasks.responder = $responderid
    LIMIT $limit OFFSET $offset
");
$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Tasks</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* Add your CSS styles here */
        /* Same CSS as in employee_account.php */
        .content-table{
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  border-collapse: collapse;
  width: 95%;
  height: 500px;
  border: 1px solid #bdc3c7;
  box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.2), -1px -1px 8px rgba(0, 0, 0, 0.2);
}

.content-table tr {
  transition: all .2s ease-in;
  cursor: pointer;
}

.content-table th,
.content-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.content-table tr:hover {
  background-color: #f5f5f5;
  transform: scale(1.02);
  box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.2), -1px -1px 8px rgba(0, 0, 0, 0.2);
}

#header {
  background-color: #16a085;
  color: #fff;
}

@media only screen and (max-width: 768px) {
  .content-table {
      width: 90%;
  }
}

.pagination {
  display: flex;
  justify-content: center;
  margin: 20px 0;
  position: absolute;
  bottom: 50px;
  left: 0;
  right: 0;
}

.pagination a {
  margin: 0 10px;
  text-decoration: none;
}

.dropdown select {
  padding: 5px;
  border-radius: 5px;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
}

.detail-button {
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  background-color: #4CAF50;
  color: white;
  cursor: pointer;
}

.detail-button:hover {
  background-color: #45a049;
}
    </style>
</head>
<body>
<table class="content-table">
    <thead>
        <tr id="header">
            <th>Task ID</th>
            <th>Title</th>
            <th>Creator</th>
            <th>Responder</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?php echo $task['taskid']; ?></td>
            <td class="editable"><?php echo $task['title']; ?></td>
            <td class="editable"><?php echo $task['creatorName']; ?></td>
            <td class="editable"><?php echo $task['responderName']; ?></td>
            <td><?php echo $task['status']; ?></td>
            <td>
            <a href="task_from_director_details.php?taskId=<?php echo $task['taskid']; ?>" class="detail-button">Detail</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>
<div id="taskDetailsModal" style="display: none;">
    <!-- Modal content will be populated here -->
</div>
</body>
</html>