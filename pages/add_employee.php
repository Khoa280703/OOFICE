<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
        .form {
            background-color: #f8f8f8;
            padding: 20px;
            width: 300px;
            margin: auto;
        }
        .label {
            display: block;
            margin-bottom: 5px;
        }
        .input, .select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        .notification {
            background-color: orange;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px auto;
            text-align: center;
            width: 50%;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            color: white;
            font-size: 1em;
            transition: opacity 0.5s ease-out;
        }
    </style>
</head>
<body>
<?php if (isset($_SESSION['notification'])): ?>
<div class="notification" id="notification">
    <?php echo $_SESSION['notification']; ?>
</div>
<?php unset($_SESSION['notification']); endif; ?>
    <form class="form" id="addEmployeeForm" action="../server/add_user.php" method="post">
        <label class="label" for="name">Name</label>
        <input class="input" type="text" name="name" id="name" required>
        <label class="label" for="username">Username</label>
        <input class="input" type="text" name="username" id="username" required>
        <label class="label" for="password">Password</label>
        <input class="input" type="password" name="password" id="password" required>
        <label class="label" for="role">Role</label>
        <select class="select" name="role" id="role" required>
            <?php foreach ($roles as $role): ?>
            <option value="<?php echo $role['roleid']; ?>"><?php echo $role['rolename']; ?></option>
            <?php endforeach; ?>
        </select>
        <label class="label" for="department">Department</label>
        <select class="select" name="department" id="department" required>
            <?php foreach ($departments as $department): ?>
            <option value="<?php echo $department['departmentid']; ?>"><?php echo $department['departmentname']; ?></option>
            <?php endforeach; ?>
        </select>
        <button class="button" type="submit">Add Employee</button>
    </form>
    <script>
    var notification = document.getElementById('notification');
    if (notification) {
        setTimeout(function() {
            notification.style.display = 'none';
        }, 5000); // Hide after 5 seconds
    }
    </script>
</body>
</html>