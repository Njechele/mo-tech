<?php
include './includes/db.php';
include 'header.php';
include "footer.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = $_POST['first_name'];
    $middle = $_POST['middle_name'];
    $last = $_POST['last_name'];
    $gender = $_POST['gender'];
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $role = $_POST['role'];

    // Check if username already exists
    $check = mysqli_query($conn, "SELECT user_id FROM users WHERE username='$user'");
    if (mysqli_num_rows($check) > 0) {
        $message = '<span style="color:red;">Username already exists. Please choose another.</span>';
    } else {
        // Hash the password before saving
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        // Tambua class_id kwa role yake, isipokuwa admin
        switch($role){
            case 'form1_teacher': $class_id = 1; break;
            case 'form2_teacher': $class_id = 2; break;
            case 'form3_teacher': $class_id = 3; break;
            case 'form4_teacher': $class_id = 4; break;
            case 'admin': $class_id = null; break;
            default: $class_id = null; break;
        }

        // Kama admin, usiweke class_id kabisa kwenye query
        if ($role === 'admin') {
            $sql = "INSERT INTO users(first_name, middle_name, last_name, gender, username, password, role) 
                    VALUES('$first','$middle','$last','$gender','$user','$hashed_pass','$role')";
        } else {
            if ($class_id === null) {
                $message = '<span style="color:red;">Unknown Role!</span>';
            } else {
                $sql = "INSERT INTO users(class_id, first_name, middle_name, last_name, gender, username, password, role) 
                        VALUES('$class_id', '$first','$middle','$last','$gender','$user','$hashed_pass','$role')";
            }
        }

        if (isset($sql) && mysqli_query($conn, $sql)) {
            $message = '<span style="color:green;">Teacher/Admin added successfully.</span>';
        } elseif (!isset($sql)) {
            // Message already set for unknown role
        } else {
            $message = '<span style="color:red;">Failed to add user. Please check your database structure.</span>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        *{
            margin: 0;
            padding: 0;
            border: none;
            box-sizing: border-box;
            text-decoration: none;
        }
        body{
            background: whitesmoke;
            margin: 0;
            padding: 0;
            background-color: whitesmoke;
        }
        .register{
            background: #FFF;
            box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
            width: 500px;
            margin: 30px auto;
            margin-top: 100px;
            text-align: center;
        }
        .register input, select{
            padding: 15px 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 70%;
            border: 2px solid transparent;
            background-color:  rgba(0, 0, 0, 0.1);
        }
        input:hover{
            border: 2px solid blue;
        }
        .register .submit{
            width: 150px;
            margin-left: 20px;
            background: blue;
            color: white;
            border-radius: 10px;
            border-radius: 5px;
            transition:  0.3s ease-in-out;
        }
        .submit:hover{
            background: rgb(8, 8, 67);
        }
        .msg {
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
        }
    </style>
    <script>
        function toggleClassSelect() {
            var role = document.getElementsByName('role')[0].value;
            var classInputs = document.querySelectorAll('.class-dependent');
            if (role === 'admin') {
                classInputs.forEach(function(el) { el.style.display = 'none'; });
            } else {
                classInputs.forEach(function(el) { el.style.display = 'block'; });
            }
        }
        window.onload = function() {
            toggleClassSelect();
        };
    </script>
</head>
<body>
    <div class="register">
        <form action="" method="POST">
            <h2>ADD TEACHERS / ADMIN</h2>
            <a href="viewTeachers.php">View Teacher</a><br>
            <?php if (!empty($message)): ?>
                <div class="msg"><?php echo $message; ?></div>
            <?php endif; ?>
            <input type="text" placeholder="First Name" name="first_name" required><br>
            <input type="text" placeholder="Middle Name" name="middle_name"><br>
            <input type="text" placeholder="Last Name" name="last_name" required><br>
            <select name="gender" required>
                <option>Male</option>
                <option>Female</option>
            </select><br>
            <input type="text" placeholder="Username" name="username" required><br>
            <input type="password" placeholder="Password" name="password" required><br>
            <select name="role" required onchange="toggleClassSelect()">
                <option value="">role</option>
                <option value="admin">admin</option>
                <option value="form1_teacher">form1_teacher</option>
                <option value="form2_teacher">form2_teacher</option>
                <option value="form3_teacher">form3_teacher</option>
                <option value="form4_teacher">form4_teacher</option>
            </select><br>
            <input type="submit" value="Register" name="submt" class="submit">
        </form>
    </div>
</body>
</html>