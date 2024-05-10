<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    }
    .slider {
        position: relative;
        width: 70%;
        height: 300px;
        overflow: hidden;
        margin: 0% auto;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background-color: #E0E0E0;
    }
    .slider-content {
        display: flex;
        transition: transform 0.3s ease-out;
        height: 100%;
    }
    .card {
        flex: 0 0 100%;
        height: 100%;
        margin-right: 20px;
        background-size: 65%; /* Adjust this value to zoom in or out the image */
        background-repeat: no-repeat; /* Prevent the image from repeating */
        background-position: right; /* Center the image */
        padding: 20px;
        box-sizing: border-box;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .card-body {
        display: flex;
        justify-content: flex-start; /* Align horizontally to the center */
        align-items: center; /* Align vertically in the center */
        height: 100%; /* Take up the full height of the .card */
    }
    .card-title {
        font-size: 180%; /* Increase the font size */
        color: #333; /* Change the text color */
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Add a text shadow */
        margin-left: 20px; /* Add some space to the left of the title */
        max-width: 50%;
        text-align: center;
    }
    .arrow {
        text-decoration: none;
        color: #333;
        font-size: 2em;
        transition: color 0.3s ease-out;
        position: absolute;
        top: 40%;
        transform: translateY(-50%);
    }

    .arrow.arrow-prev {
        left: 10%;
    }

    .arrow.arrow-next {
        right: 10%;
    }
    .arrow:hover {
        color: #888;
    }
    .notification {
    background-color: #ff9800; /* Softer orange */
    border: 1px solid #ffa726; /* Softer border */
    padding: 10px; /* Smaller padding */
    margin: 10px auto; /* Smaller margin */
    text-align: center;
    width: 50%;
    border-radius: 5px; /* Less rounded corners */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Softer shadow */
    color: white; /* White text */
    font-size: 1em; /* Smaller font size */
    transition: opacity 0.5s ease-out; /* Transition for the opacity */
    }
    .form {
        background-color: #f8f8f8;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .form-input {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
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
    </style>
</head>
<body>
<?php if (isset($_SESSION['notification'])): ?>
<div class="notification" id="notification">
    <?php echo $_SESSION['notification']; ?>
</div>
<?php unset($_SESSION['notification']); endif; ?>
<a href="#" class="arrow arrow-prev"><ion-icon name="chevron-back-outline"></ion-icon></a>
    <div class="slider">
        <div class="slider-content">
            <?php foreach ($departments as $index => $department): ?>
            <div class="card" style="background-image: url('<?php echo $department['image_url']; ?>');">
                <div class="card-body">
                    <h2 class="card-title"><?php echo $department['departmentname']; ?></h2>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <a href="#" class="arrow arrow-next"><ion-icon name="chevron-forward-outline"></ion-icon></a>
    <br>
    <div style="display: flex; justify-content: space-between; max-width: 600px; margin: auto;">
    <!-- Add Department Form -->
    <form class="form" id="addDepartmentForm" action="../server/add_department.php" method="post" style="flex: 1; margin-right: 10px;">
        <label for="departmentName">Department Name</label>
        <input class="form-input" type="text" name="departmentName" id="departmentName" required>
        <label for="imageUrl">Image URL</label>
        <input class="form-input" type="text" name="imageUrl" id="imageUrl" required>
        <button class="form-button" type="submit">Add Department</button>
    </form>

    <!-- Delete Department Form -->
    <form class="form" id="deleteDepartmentForm" action="../server/delete_department.php" method="post" style="flex: 1; margin-left: 10px;">
        <label for="departmentName">Department Name</label>
        <select class="form-input" name="departmentName" id="departmentName" required>
            <?php foreach ($departments as $department): ?>
            <option value="<?php echo $department['departmentname']; ?>"><?php echo $department['departmentname']; ?></option>
            <?php endforeach; ?>
        </select>
        <button class="form-button" type="submit">Delete Department</button>
    </form>
</div>

<script>
var sliderContent = document.querySelector('.slider-content');
var currentSlide = 0;

document.querySelector('.arrow-prev').addEventListener('click', function(e) {
    e.preventDefault();
    var cardWidth = document.querySelector('.card').clientWidth + 20; // Add the margin
    if (currentSlide > 0) {
        currentSlide--;
        sliderContent.style.transform = 'translateX(-' + (cardWidth * currentSlide) + 'px)';
    }
});

document.querySelector('.arrow-next').addEventListener('click', function(e) {
    e.preventDefault();
    var cardWidth = document.querySelector('.card').clientWidth + 20; // Add the margin
    if (currentSlide < <?php echo count($departments) - 1; ?>) {
        currentSlide++;
        sliderContent.style.transform = 'translateX(-' + (cardWidth * currentSlide) + 'px)';
    }
});
</script>
<script>
var notification = document.getElementById('notification');
if (notification) {
    setTimeout(function() {
        notification.style.display = 'none';
    }, 2000); // Hide after 5 seconds
}
</script>
</body>
</html>