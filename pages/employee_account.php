<?php
$limit = 6; // Number of users per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$result = $conn->query('SELECT COUNT(*) AS total FROM users');
$totalUsers = $result->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $limit);

$users = array_slice($users, $offset, $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
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

.edit-button {
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  background-color: #4CAF50;
  color: white;
  cursor: pointer;
}

.edit-button:hover {
  background-color: #45a049;
}

.delete-button {
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  background-color: #4CAF50;
  color: white;
  cursor: pointer;
}

.delete-button:hover {
  background-color: #45a049;
}
    </style>
</head>
<body>
<table class="content-table">
                    <thead>
                    <tr id="header">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
    <td><?php echo $user['id']; ?></td>
    <td class="editable"><?php echo $user['name']; ?></td>
    <td class="editable"><?php echo $user['username']; ?></td>
    <td class="editable"></td>
    <td class="dropdown">
        <select disabled>
            <?php foreach ($roles as $role): ?>
            <option value="<?php echo $role['roleid']; ?>" <?php if ($role['rolename'] == $user['rolename']) echo 'selected'; ?>>
                <?php echo $role['rolename']; ?>
            </option>
            <?php endforeach; ?>
        </select>
    </td>
    <td class="dropdown">
        <select disabled>
            <?php foreach ($departments as $department): ?>
            <option value="<?php echo $department['departmentid']; ?>" <?php if ($department['departmentname'] == $user['departmentname']) echo 'selected'; ?>>
                <?php echo $department['departmentname']; ?>
            </option>
            <?php endforeach; ?>
        </select>
    </td>
    <td>
    <button class="edit-button">Edit</button>
    <button class="delete-button">Delete</button>
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
</body>
<script>//edit users button
    $('.edit-button').click(function() {
    var $row = $(this).closest('tr');
    var $editableFields = $row.find('.editable');
    var $passwordField = $row.find('.editable').eq(2);
    var $dropdowns = $row.find('.dropdown select');

    if ($editableFields.attr('contenteditable') == 'true') {
        // If the row is currently editable, make it non-editable
        $editableFields.attr('contenteditable', 'false');
        $dropdowns.prop('disabled', true);
        $(this).text('Edit');

        // Replace the password input field with a normal td
        var password = $passwordField.find('input').val();
        $passwordField.html('');

        // Save the changes
        var id = $row.find('td').eq(0).text();
        var name = $row.find('.editable').eq(0).text();
        var username = $row.find('.editable').eq(1).text();
        var roleid = $row.find('.dropdown select').eq(0).val();
        var departmentid = $row.find('.dropdown select').eq(1).val();

        $.post('../server/update_user.php', {id: id, name: name, username: username, password: password, roleid: roleid, departmentid: departmentid}, function(response) {
            // Handle the response from the server
        });
    } else {
        // If the row is currently non-editable, make it editable
        $editableFields.attr('contenteditable', 'true');
        $dropdowns.prop('disabled', false);
        $(this).text('Save');

        // Replace the password td with an input field of type password
        var password = $passwordField.text();
        $passwordField.html('<input type="password" value="' + password + '">');
    }
});
</script>

<script>//delete users button
$('.delete-button').click(function() {
    var $row = $(this).closest('tr');
    var id = $row.find('td').eq(0).text();

    $.post('../server/delete_user.php', {id: id}, function(response) {
        // Parse the response as JSON
        var parsedResponse = JSON.parse(response);

        // Handle the response from the server
        if (parsedResponse.success) {
            $row.remove(); // Remove the row from the DOM
        } else {
            alert('Failed to delete user');
        }
    });
});
</script>
</html>