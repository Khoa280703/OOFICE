<?php
if (!isset($_SESSION['user'])) {
    echo "You are not logged in!";
    exit;
}

$user = $_SESSION['user'];

$stmt = $conn->prepare('SELECT roles.rolename, departments.departmentname FROM users 
                        INNER JOIN roles ON users.roleid = roles.roleid 
                        INNER JOIN departments ON users.departmentid = departments.departmentid 
                        WHERE users.id = ?');
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$userInfo = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
.card-container {
  perspective: 700;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.businesscard {
  position: relative;
  height: 400px;
  width: 350px;
  color: #252B37;
  text-transform: uppercase;
  transition: all 0.9s ease-in;
  transform-style: preserve-3d;
  box-shadow: 0px 10px 30px -5px rgba(0, 0, 0, 0.3);
}

.businesscard:hover {
  transform: rotateY(180deg);
}

.front, .back {
  height: 100%;
  width: 100%;
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  backface-visibility: hidden;
  padding: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.front {
  background-color: #151823;
  color: #D3D4D9;
}

.back {
  transform: rotateY(180deg);
  background-color: #292258;
  color: white;
}

.card-hr {
  border: 1px solid #3A869E;
  width: 100%;
}

.card-h1 {
  font-size: 1.5em;
  margin-bottom: 10px;
}

.card-img {
  border-radius: 50%;
  width: 70px;
  height: 70px;
  object-fit: cover;
  margin-bottom: 15px;
}

.card-p {
  font-size: 0.8em;
  font-weight: 700;
  margin-bottom: 10px;
}

@keyframes textColor {
  0% {
    color: #7e0fff;
  }
  50% {
    color: #0fffc1;
  }
  100% {
    color: #7e0fff;
  }
}
.form {
        background-color: #f8f8f8;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        color: black;
    }
    .form-input {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .form-label {
        display: block;
        margin-bottom: 5px;
    }
    .form-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .form-button:hover {
        background-color: #45a049;
    }
    .card-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .card-button:hover {
        background-color: #45a049;
    }
    </style>
</head>
<body>
<div class="card-container">
  <div class="businesscard">
    <div class="front">
      <img class="card-img" src="https://img.myloview.fr/stickers/default-avatar-profile-icon-vector-social-media-user-400-202768327.jpg">
      <h1 class="card-h1"><?php echo $user['name']; ?></h1>
      <hr class="card-hr"/>
      <br>
      <p class="card-p"><?php echo $userInfo['rolename'];?></p>
    </div>
    <div class="back">
  <p class="card-p">Name: <span id="name"><?php echo $user['name']; ?></span></p>
  <p class="card-p">Username: <span id="username"><?php echo $user['username']; ?></span></p>
  <p class="card-p">Department: <?php echo $userInfo['departmentname']; ?> </p>
  <br>
  <button id="editButton" class="card-button">Edit</button>
  <form id="editForm" class="form" action="../server/update_current_user.php" method="POST" style="display: none;">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <label class="form-label" for="newName">New name:</label>
    <input class="form-input" type="text" id="newName" name="newName">
    <label class="form-label" for="newUsername">New username:</label>
    <input class="form-input" type="text" id="newUsername" name="newUsername">
    <label class="form-label" for="newPassword">New password:</label>
    <input class="form-input" type="password" id="newPassword" name="newPassword">
    <button type="button" id="backButton" class="form-button">Back</button>
    <br>
    <br>
    <input type="submit" value="Submit" class="form-button">
</form>
    </div>
</div>
</div>
<script>
document.getElementById('editButton').addEventListener('click', function() {
    // Hide the user information and Edit button
    var userInfo = document.querySelectorAll('.back .card-p');
    for (var i = 0; i < userInfo.length; i++) {
        userInfo[i].style.display = 'none';
    }
    this.style.display = 'none';

    // Show the form
    document.getElementById('editForm').style.display = 'block';
});

document.getElementById('backButton').addEventListener('click', function() {
    // Hide the form
    document.getElementById('editForm').style.display = 'none';

    // Show the user information and Edit button
    var userInfo = document.querySelectorAll('.back .card-p');
    for (var i = 0; i < userInfo.length; i++) {
        userInfo[i].style.display = 'block';
    }
    document.getElementById('editButton').style.display = 'block';
});
</script>
</body>
</html>