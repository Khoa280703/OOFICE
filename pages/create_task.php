<?php
require '../database.php';

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
$query = $userRole == 3
    ? "SELECT * FROM users WHERE roleid = '$roleToFetch' AND departmentid = '$userDepartment'"
    : "SELECT * FROM users WHERE roleid = '$roleToFetch'";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Create Task</title>
    <style>
    .create-task-form {
        background-color: #fff;
        padding: 20px;
        margin: 20px; /* Add margin here */
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .create-task-form input, .create-task-form textarea, .create-task-form select {
        width: 100%;
        margin-bottom: 10px;
        padding: 10px;
        box-sizing: border-box;
    }
    .create-task-form input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
    }
    .create-task-form input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>
<form action="../server/create_task_handler.php" method="post" enctype="multipart/form-data" class="create-task-form">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" required><br>
    <label for="description">Description:</label><br>
    <textarea id="description" name="description"></textarea><br>
    <label for="descriptionFile">Or upload a description file:</label><br>
    <input type="file" id="descriptionFile" name="descriptionFile" accept=".txt"><br>
    <label for="deadline">Deadline:</label><br>
    <input type="datetime-local" id="deadline" name="deadline" required><br>
    <label for="responder">To:</label><br>
    <select id="responder" name="responder" required>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select><br>
    <input type="submit" value="Create Task">
</form>
</body>
</html>