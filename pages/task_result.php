<?php
$limit = 6; // Number of results per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$creatorid = $_SESSION['user']['id'];

$result = $conn->query("SELECT COUNT(*) AS total FROM taskresults INNER JOIN tasks ON taskresults.taskid = tasks.taskid WHERE tasks.creator = $creatorid");
$totalResults = $result->fetch_assoc()['total'];
$totalPages = ceil($totalResults / $limit);

$result = $conn->query("SELECT taskresults.id, taskresults.taskid, tasks.title, users.name AS creatorName, taskresults.status FROM taskresults INNER JOIN tasks ON taskresults.taskid = tasks.taskid INNER JOIN users ON taskresults.creator = users.id WHERE tasks.creator = $creatorid LIMIT $limit OFFSET $offset");
$taskResults = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .accept-button, .reject-button {
            padding: 5px 10px;
            border-radius: 5px;
            border: none;
            color: white;
            cursor: pointer;
        }

        .accept-button {
            background-color: #4CAF50;
        }

        .accept-button:hover {
            background-color: #45a049;
        }

        .reject-button {
            background-color: #f44336;
        }

        .reject-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
<table class="content-table">
<thead>
    <tr id="header">
        <th>ID</th>
        <th>Task ID</th>
        <th>Title</th> <!-- Add this line -->
        <th>Creator</th>
        <th>Status</th>
        <th></th>
    </tr>
</thead>
<tbody>
    <?php foreach ($taskResults as $taskResult): ?>
    <tr>
        <td><?php echo $taskResult['id']; ?></td>
        <td><?php echo $taskResult['taskid']; ?></td>
        <td><?php echo $taskResult['title']; ?></td> <!-- Add this line -->
        <td><?php echo $taskResult['creatorName']; ?></td>
        <td><?php echo $taskResult['status']; ?></td>
        <td>
        <a href="task_result_details.php?resultId=<?php echo $taskResult['id']; ?>" class="detail-button">Detail</a>
        <button class="accept-button"  data-resultid="<?php echo $taskResult['id']; ?>">Accept</button>
        <button class="reject-button" data-resultid="<?php echo $taskResult['id']; ?>">Reject</button>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'style="background-color: #4CAF50; color: white;"'; ?>><?php echo $i; ?></a>
    <?php endfor; ?>
</div>
<script>
    $('.accept-button').click(function() {
    var resultId = $(this).data('resultid');
    $.post('../server/update_task_result_status.php', { resultId: resultId, status: 'Accept' }, function(response) {
        if (response.success) {
            location.reload();
        } else {
            alert('Error updating task result status.');
        }
    }, 'json');
});

$('.reject-button').click(function() {
    var resultId = $(this).data('resultid');
    $.post('../server/update_task_result_status.php', { resultId: resultId, status: 'Reject' }, function(response) {
        if (response.success) {
            location.reload();
        } else {
            alert('Error updating task result status.');
        }
    }, 'json');
});
</script>
</body>
</html>
