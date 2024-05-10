<?php
session_start();
require '../database.php';
$taskId = isset($_GET['taskId']) ? $_GET['taskId'] : null;

if ($taskId) {
    // Fetch the task details from the database
    $sql = "SELECT tasks.taskid, tasks.title, tasks.description, tasks.deadline, creator.name AS creatorName, responder.name AS responderName, tasks.status
            FROM tasks
            INNER JOIN users AS creator ON tasks.creator = creator.id
            INNER JOIN users AS responder ON tasks.responder = responder.id
            WHERE tasks.taskid = $taskId";
    $result = $conn->query($sql);
    $task = $result->fetch_assoc();

    // Fetch the role and department of the current user
    $userId = $_SESSION['user']['id'];
    $sql = "SELECT roleid, departmentid FROM users WHERE id = $userId";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
    $userRole = $user['roleid'];
    $userDepartment = $user['departmentid'];

    // Determine the role of the users to fetch based on the role of the current user
    $roleToFetch = $userRole == 2 ? 3 : 4;

    // Fetch users with the determined role and, if the current user's role is 3, the same department
    $sql = $userRole == 3
        ? "SELECT id, name FROM users WHERE roleid = '$roleToFetch' AND departmentid = '$userDepartment'"
        : "SELECT id, name FROM users WHERE roleid = '$roleToFetch'";
    $result = $conn->query($sql);
    $employees = $result->fetch_all(MYSQLI_ASSOC);

    $sql = "SELECT roles.rolename FROM users INNER JOIN roles ON users.roleid = roles.roleid WHERE users.id = $userId";
    $result = $conn->query($sql);
    $userRole = $result->fetch_assoc()['rolename'];
} else {
    // Redirect to the tasks page if no task id is provided
    header('Location: tasks.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Task Details</title>
    <!-- Add your CSS styles here -->
    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto);
        @import url(https://fonts.googleapis.com/css?family=Handlee);

        .task-details-body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #91D1D3;
            font-family: 'Roboto', sans-serif;
        }

        .paper {
            position: relative;
            width: 90%;
            max-width: 800px;
            min-width: 400px;
            height: 480px;
            margin: 0 auto;
            background: #fafafa;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
            overflow: hidden;
        }
        .paper:before {
            content: '';
            position: absolute;
            top: 0; bottom: 0; left: 0;
            width: 60px;
            background: radial-gradient(#575450 6px, transparent 7px) repeat-y;
            background-size: 30px 30px;
            border-right: 3px solid #D44147;
            box-sizing: border-box;
        }

        .paper-content {
            position: absolute;
            top: 30px; right: 0; bottom: 30px; left: 60px;
            background: linear-gradient(transparent, transparent 28px, #91D1D3 28px);
            background-size: 30px 30px;
        }

        .paper-content textarea {
            width: 100%;
            max-width: 100%;
            height: 100%;
            max-height: 100%;
            line-height: 30px;
            padding: 0 10px;
            border: 0;
            outline: 0;
            background: transparent;
            color: mediumblue;
            font-family: 'Handlee', cursive;
            font-weight: bold;
            font-size: 18px;
            box-sizing: border-box;
            z-index: 1;
        }
        .back-button {
            position: absolute;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #D44147;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .edit-button {
            position: absolute;
            bottom: 20px;
            right: 100px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        #editForm {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
            justify-content: center;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        #editForm label {
            display: flex;
            flex-direction: column;
            width: 80%;
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
        }

        #editForm input[type="text"],
        #editForm input[type="date"],
        #editForm textarea {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 5px;
        }

        #editForm .edit-button {
            position: absolute; /* Add this line */
            right: 10%; /* Add this line */
            bottom: 0%; /* Add this line */
            align-self: flex-end;
            margin-top: 10px;
        }
    </style>
</head>
<body class="task-details-body">
<!-- Add more fields as needed -->
<div class="paper">
    <div id="taskDetails" class="paper-content">
        <textarea autofocus>Task ID: <?php echo $task['taskid']; ?>&#10;
Title: <?php echo $task['title']; ?>&#10;
Description: <?php echo $task['description']; ?>&#10; &#10; 
Creator: <?php echo $task['creatorName']; ?>&#10;
Responder: <?php echo $task['responderName']; ?>&#10;
Deadline: <?php echo $task['deadline']; ?>&#10;
Status: <?php echo $task['status']; ?>
</textarea>
    </div>
    <div id="editButtonDiv">
        <button id="editButton" class="edit-button">Edit</button>
    </div>
    <form id="editForm" action="../server/edit_task_handler.php" method="post" enctype="multipart/form-data" class="paper-content" style="display: none;">
        <!-- Edit form here -->
        <input type="hidden" name="taskid" value="<?php echo $task['taskid']; ?>">
        <label>
            Title:
            <input type="text" name="title" value="<?php echo $task['title']; ?>">
        </label>
        <label>
            Description:
            <textarea name="description"><?php echo $task['description']; ?></textarea>
            <label for="descriptionFile">Or upload a description file:</label>
            <input type="file" id="descriptionFile" name="descriptionFile" accept=".txt">
        </label>
        <label>
            Responder:
            <select name="responder">
                <?php foreach ($employees as $employee): ?>
                    <option value="<?php echo $employee['id']; ?>" <?php echo $task['responderName'] == $employee['name'] ? 'selected' : ''; ?>>
                        <?php echo $employee['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Deadline:
            <input type="datetime-local" name="deadline" value="<?php echo date('Y-m-d\TH:i', strtotime($task['deadline'])); ?>">
        </label>
        <label>
            Status:
            <select name="status">
                <option value="Not accepted" <?php echo $task['status'] == 'Not accepted' ? 'selected' : ''; ?>>Not accepted</option>
                <option value="Accepted" <?php echo $task['status'] == 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
                <option value="Need modify" <?php echo $task['status'] == 'Need modify' ? 'selected' : ''; ?>>Need modify</option>
            </select>
        </label>
        <input type="submit" value="Save" class="edit-button">
    </form>
</div>
<a href="<?php echo $userRole . '.php'; ?>" class="back-button">Back</a>
<script>
    document.getElementById('editButton').addEventListener('click', function() {
        document.getElementById('taskDetails').style.display = 'none';
        document.getElementById('editButtonDiv').style.display = 'none';
        document.getElementById('editForm').style.display = 'block';
    });
</script>
</body>
</html>