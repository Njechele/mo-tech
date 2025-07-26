<?php
include './includes/db.php';
include 'header.php';
include "footer.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = $_POST['first_name'];
    $middle = $_POST['middle_name'];
    $last = $_POST['last_name'];
    $role = $_POST['student'];
    $parent = $_POST['parent_name'];
    $parent_email = $_POST['parent_email'] ?? ''; // Parent email si lazima
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $gender = $_POST['gender'];
    $class_id = $_POST['class_id'];
    $stream = $_POST['stream'];

    // Check if username already exists
    $check = mysqli_query($conn, "SELECT user_id FROM users WHERE username='$user'");
    if (mysqli_num_rows($check) > 0) {
        $message = '<span style="color:red;">Username already exists. Please choose another.</span>';
    } else {
        // Hash the password before saving
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(class_id,first_name,middle_name,last_name,parent_name,parent_email,username,password,role,gender,stream) 
        VALUES('$class_id','$first','$middle','$last','$parent','$parent_email','$user','$hashed_pass','$role','$gender','$stream')";
        if (mysqli_query($conn, $sql)) {
            $message = '<span style="color:green;">Student added successfully.</span>';
        } else {
            $message = '<span style="color:red;">Form not processed!</span>';
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
            outline: none;
            box-sizing: border-box;
        }
        body{
            font-family: 'poppins'; 
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
            border: none;
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
</head>
<body>
    <div class="register">
        <form action="" method="POST">
            <h2>ADD STUDENT</h2>
            <?php if (!empty($message)): ?>
                <div class="msg"><?php echo $message; ?></div>
            <?php endif; ?>
            <input type="hidden" name="student" value="student"><br>
            <input type="hidden" name="class_id" value="<?php echo $class_id ?? ''; ?>"><br>
            <input type="text" placeholder="First Name" name="first_name" required><br>
            <input type="text" placeholder="Middle Name" name="middle_name" required><br>
            <input type="text" placeholder="Last Name" name="last_name" required><br>
            <select name="class_id" id="class_id" required>
                <option value="" style="text-align:center;">........choose student level........</option>
                <?php  
                    $class = mysqli_query($conn, "SELECT * FROM class");
                    while($row = mysqli_fetch_assoc($class)){
                        echo '<option value="' .$row['class_id']. '">' . $row['class_name'].' </option>';
                    }
                ?>
            </select><br>
            <select name="stream" style="text-align:center;" required>
                <option value="">........Choose combination........</option>
                <option value="art">Art</option>
                <option value="science">Science</option>
            </select><br>
            <input type="text" placeholder="Parent Name" name="parent_name" required><br>
            <input type="email" placeholder="Parent Email (optional)" name="parent_email"><br>
            <select name="gender" required>
                <option>Male</option>
                <option>Female</option>
            </select><br>
            <input type="text" placeholder="Username" name="username" required><br>
            <input type="password" placeholder="password" name="password" required><br>
            <input type="submit" value="Register" name="submt" class="submit">
        </form>
    </div>
</body>
</html>