<?php
session_start();
include "header.php";
include "./includes/db.php";
include "footer.php";

$id = $_GET['user_id'];
$returnPage = $_GET['return'];

$sql = "SELECT * FROM users WHERE user_id = '$id'";
$results = mysqli_query($conn, $sql);
$student = mysqli_fetch_array($results);

// Chukua class zote kwa ajili ya select
$class_query = mysqli_query($conn, "SELECT * FROM class");

// process 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['first_name'];
    $mname = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $lname = $_POST['last_name'];
    $parent = $_POST['parent_name'];
    $class_id = $_POST['class_id'];
    $stream = $_POST['stream'];
    $username = $_POST['username'];
    $returnPage = $_POST['returnPage'];

    $update = "UPDATE users SET first_name='$fname', middle_name = '$mname', last_name = '$lname', parent_name = '$parent', class_id='$class_id', stream='$stream', username='$username' WHERE user_id='$id'";

    if (mysqli_query($conn, $update) == TRUE) {
        echo "<script>alert('Student edited successfully');window.location.href='$returnPage';</script>";
    } else {
        echo "<script>alert('Edited Not successfully');window.location.href='$returnPage';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Management System</title>
<style>
    *{
       margin: 0;
       padding: 0; 
       box-sizing: border-box;
    }
    body{
        background: whitesmoke;
        font-family: 'poppins';
    }
    h1{
        text-align: center;
        margin-bottom: 30px;
    }
    label{
        margin-left: 50px;
    }
    .container{
        background: #FFF;
        box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
        width: 500px;
        margin: 100px auto;
    }
    .container input, .container select{
        padding: 15px 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        width: 80%;
        margin-left: 50px;
        border: 2px solid transparent;
        background-color:  rgba(0, 0, 0, 0.1);
    }
    .container input:focus, .container select:focus{
        border: 2px solid blue;
    }
    button{
        background: blue;
        color: white;
        border: none;
        padding: 10px 50px;
        border-radius: 10px;
        transition: 0.3s ease-in-out;
        margin-left: 33%;
        margin-bottom: 30px;
    }
    button:hover{
        background: rgb(8, 8, 67);
        letter-spacing: 1.3px;
    }
</style>
</head>
<body>
<form action="" method="POST">
    <div class="container">
        <h1>Update student information</h1>
        <input type="hidden" name="user_id" value="<?php echo $id; ?>">
        <input type="hidden" name="returnPage" value="<?php echo $returnPage; ?>">

        <label>Parent Name</label><br>
        <input type="text" name="parent_name" class="form-control" value="<?php echo htmlspecialchars($student['parent_name']); ?>" required><br><br>

        <label>First Name</label><br>
        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($student['first_name']); ?>" required><br><br>

        <label>Middle Name</label><br>
        <input type="text" name="middle_name" class="form-control" value="<?php echo htmlspecialchars($student['middle_name']); ?>" required><br><br>

        <label>Last Name</label><br>
        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($student['last_name']); ?>" required><br><br>

        <label>Username</label><br>
        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($student['username']); ?>" required><br><br>

        <label>Class</label><br>
        <select name="class_id" required>
            <option value="">--Select Class--</option>
            <?php  
                while($row = mysqli_fetch_assoc($class_query)){
                    $selected = ($student['class_id'] == $row['class_id']) ? 'selected' : '';
                    echo '<option value="' . $row['class_id'] . '" ' . $selected . '>' . htmlspecialchars($row['class_name']) . '</option>';
                }
            ?>
        </select><br><br>

        <label>Stream</label><br>
        <select name="stream" style="text-align:center;" required>
            <option value="">........Choose combination........</option>
            <option value="art" <?php if($student['stream'] == 'art') echo 'selected'; ?>>Art</option>
            <option value="science" <?php if($student['stream'] == 'science') echo 'selected'; ?>>Science</option>
        </select><br><br>

        <button type="submit">Update</button>
    </div>
</form>
</body>
</html>
