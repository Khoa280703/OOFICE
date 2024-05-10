<?php
session_start();
require '../database.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['roleid'] != 1) {
    header('Location: http://localhost/Landing/index.html');
    exit;
}
$stmt = $conn->prepare('SELECT users.id, users.name, users.username, users.password, roles.rolename, departments.departmentname FROM users 
                        INNER JOIN roles ON users.roleid = roles.roleid 
                        INNER JOIN departments ON users.departmentid = departments.departmentid');
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$roles = $conn->query('SELECT * FROM roles')->fetch_all(MYSQLI_ASSOC);
$departments = $conn->query('SELECT * FROM departments')->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet"  href="../assets/css/dashboard.css?v=1.2">
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <div class="logo">
                        <span class="title">OOFFICE</span>
                    </div>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="people-circle-outline"></ion-icon>
                        </span>
                        <span class="title">Employees Account</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="business-outline"></ion-icon>
                        </span>
                        <span class="title">Departments</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="person-add-outline"></ion-icon>
                        </span>
                        <span class="title">Add Employees</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="person-outline"></ion-icon>
                        </span>
                        <span class="title">Account Info</span>
                    </a>
                </li>

                <li>
                    <a href="../server/logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="user">
                    <img src="https://img.myloview.fr/stickers/default-avatar-profile-icon-vector-social-media-user-400-202768327.jpg" alt="">
                </div>
            </div>
            <div class="content" id="employees-account-content" style="display: none;">
                <!-- Content for Employees Account page goes here -->
                <h1 class="decorated-header">Employees account</h1>
                <?php include 'employee_account.php'; ?>
            </div>
            <div class="content" id="departments-content" style="display: none;">
                <!-- Content for Departments page goes here -->
                <h1 class="decorated-header">Department</h1> 
                <?php include 'slider.php'; ?>
            </div>

            <div class="content" id="add-employees-content" style="display: none;">
                <!-- Content for Add Employees page goes here -->
            <h1 class="decorated-header">Add Employees</h1>
            <?php include 'add_employee.php'; ?>
            </div>
            <div class="content" id="account-info-content" style="display: none;">
                <!-- Content for Account Info page goes here -->
                <h1 class="decorated-header">Account Info</h1>
                <?php include 'account_info.php'; ?>
            </div>
        </div>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  // Show the current page on load
  var currentPage = localStorage.getItem('currentPage');
  if (currentPage) {
    $(".content").hide(); // Hide all content divs
    $("#" + currentPage).show(); // Show the current page
  }
 else {
    $(".content").hide(); // Hide all content divs
    $("#employees-account-content").show(); // Show the "Employees Account" page by default
  }

  $("li a").click(function(e){
    if ($(this).find('.title').text() !== 'Sign Out') {
      e.preventDefault(); // Prevent the default action (navigation)
      var contentId = "";
      switch($(this).find('.title').text()) {
        case "Employees Account":
          contentId = "employees-account-content";
          break;
        case "Departments":
          contentId = "departments-content";
          break;
        case "Add Employees":
            contentId = "add-employees-content";
            break;
        case "Account Info":
            contentId = "account-info-content";
            break;
      }

      $(".content").hide(); // Hide all content divs
      $("#" + contentId).show(); // Show the appropriate content div

      // Store the current page in local storage
      localStorage.setItem('currentPage', contentId);
    }
  });
}); 
</script>
</body>
</html>