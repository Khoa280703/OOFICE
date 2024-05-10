<?php
session_start();
require '../database.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['roleid'] != 2) {
    header('Location: http://localhost/Landing/index.html');
    exit;
}
$stmt = $conn->prepare('SELECT tasks.taskid, tasks.title, tasks.description, tasks.deadline, creatorUser.name as creatorName, responderUser.name as responderName, tasks.status 
                        FROM tasks 
                        INNER JOIN users as creatorUser ON tasks.creator = creatorUser.id 
                        INNER JOIN users as responderUser ON tasks.responder = responderUser.id');
$stmt->execute();
$tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
                            <ion-icon name="documents-outline"></ion-icon>
                        </span>
                        <span class="title">Tasks</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="create-outline"></ion-icon>
                        </span>
                        <span class="title">Create Task</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </span>
                        <span class="title">Task Result</span>
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
            <div class="content" id="task-list-content" style="display: none;">
                <h1 class="decorated-header">Tasks</h1>
                <?php include 'tasks.php'; ?>
            </div>
            <div class="content" id="create-task-content" style="display: none;">
                <h1 class="decorated-header">Create Task</h1>
                <?php include 'create_task.php'; ?>
            </div>
            <div class="content" id="task-result-content" style="display: none;">
                <h1 class="decorated-header">Task Result</h1>
                <?php include 'task_result.php'; ?>
            </div>
            <div class="content" id="account-info-content" style="display: none;">
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
    $("#task-list-content").show(); // Show the "Tasks From Director" page by default
  }

  $("li a").click(function(e){
    if ($(this).find('.title').text() !== 'Sign Out') {
      e.preventDefault(); // Prevent the default action (navigation)
      var contentId = "";
      switch($(this).find('.title').text()) {
        case 'Tasks':
          contentId = 'task-list-content';
          break;
        case 'Create Task':
          contentId = 'create-task-content';
          break;
        case 'Task Result':
          contentId = 'task-result-content';
          break;
        case 'Account Info':
          contentId = 'account-info-content';
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