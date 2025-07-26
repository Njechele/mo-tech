<?php
session_start();
include 'includes/db.php';
include "footer.php";

if(!isset($_SESSION['user_id']) || !isset($_SESSION['role'])){
    header("location: index.php");
    exit();    
}
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
if($_SESSION['role'] == 'admin'){
    include 'header.php';
}elseif($_SESSION['role'] == 'student'){
    include 'parent_header.php';
}else{
    include 'header2.php';
}

$loginErrorMessage = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($new_password !== $confirm_password){
        $loginErrorMessage = 'Password do not match!';
    }elseif(strlen($new_password) < 6){
        $loginErrorMessage = 'Password must be at least 6 characters!';
    }else{
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = '$hashed_password' WHERE user_id ='$user_id' AND role = '$role'";
        if($conn->query($sql) === TRUE){
            $loginErrorMessage  = 'Password changed successfully!';
        }else{
            $loginErrorMessage  = 'Failed to change password!';
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
            text-decoration: none;
            background-color: whitesmoke;
        }
        .change{
            background: #FFF;
            box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
            width: 500px;
            margin: 30px auto;
            margin-top: 150px;
        }
        .change h2{
            margin: 10px;
            padding: 5px;
            text-align: center;
            width: 80%;
        }
        .succes, .error{
            background: rgba(2, 169, 49, 0.69);
            border: none;
            text-align: center;
            margin-bottom: 10px;
            padding: 5px;
            color: #fff; 
            width: 100%; 
        }  
        form {
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        form input{
            padding: 15px 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 100%;
            border: 2px solid transparent;
            background-color:  rgba(0, 0, 0, 0.1);
        }
        form input:focus{
            border: 2px solid blue;
        }
        .btn{
            background: blue;
            color: white;
            border: none;
            padding: 10px 50px;
            border-radius: 10px;
            transition: 0.3s ease-in-out;
        }
        .btn:hover{
            background: rgb(8, 8, 67);
            letter-spacing: 1.3px;
        }
    </style>
</head>
<body>
    <div class="change">
        <form action="" method="POST">
            <h2>EDIT PASSWORD</h2>
            <?php if ($loginErrorMessage): ?>
                <div class="succes"><?php echo $loginErrorMessage; ?></div>
            <?php endif; ?>           
            <input type="password" placeholder="New Password" name="new_password" required><br>
            <input type="password" placeholder="Confirm Password" name="confirm_password" required><br>
            <button type="submit" class="btn">Change</button>      
  </form>
</div>

</body>
</html>