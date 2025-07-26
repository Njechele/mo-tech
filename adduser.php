<?php
include './includes/db.php';
include "footer.php";

// include 'header.php';

$class_id =$_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = $_POST['first_name'];
    $middle = $_POST['middle_name'];
    $last = $_POST['last_name'];
    $parent = $_POST['parent_name'];
    $regno = $_POST['reg_no'];
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $class_id = $_POST['class_id'];
  
$sql = "INSERT INTO users(first_name,middle_name,last_name,parent_name, reg_no,username,password,role,gender,class_id) 
value('$first','$middle','$last','$parent','$regno','$user','$pass','$role','$gender','$class_id')";
     if( mysqli_query($conn, $sql)) {
        header('Location: viewUsers.php');
    } else {
        echo "Form not processed!";
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
}
body{
    font-family: 'poppins'; 
    background-color: whitesmoke;
}
.register{
    clear: auto;
    margin-top: 80px;
    background: #fff;
    box-shadow: 10px 15px 15px rgba(0, 0, 0, 0.1);
    width: 30%;
    margin-left: 35%;
    height: 85vh;
    border: 2px solid;
    text-align: center;
}
.register h2{
    border: 1px solid;
    margin: 10px;
    padding: 5px;
    text-align: center;
}
.register input{
    padding: 5px;
    margin: 10px;
    width: 70%;
}                                                               
.register select{
    padding: 5px;
    margin: 10px;
    width: 73%;
}
.register .submit{
    width: 150px;
    margin-left: 20px;
    background: blue;
    color: white;
    border: none;
    border-radius: 5px;
}

    </style>

</head>
<body>
    
        <div class="register">
        <form action="" method="POST">
            <a href="viewUsers.php">VIEW USERS</a>
            <h2>ADD USER</h2>
            <h3> class id: <?php echo $class_id;?></h3>
            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
             <select name="role">
                <option>role</option>
                <option>admin</option>
                <option>student</option>
                <option>form1_teacher</option>
                <option>form2_teacher</option>
                <option>form3_teacher</option>
                <option>form4_teacher</option>

            </select><br>
            <br>
            <input type="text" placeholder="First Name" name="first_name" ><br>
            <input type="text" placeholder="Middle Name" name="middle_name" ><br>
            <input type="text" placeholder="Last Name" name="last_name" ><br>
            <input type="text" placeholder="Parent Name" name="parent_name" ><br>
            <select name="gender">
                <option>Male</option>
                <option>Female</option>
                <input type="text" placeholder="Reg No" name="reg_no" ><br>
                <input type="text" placeholder="Username" name="username" ><br>
            <input type="password" placeholder="password" name="password" required><br>
            <input type="submit" value="Register" name="submt" class="submit">
        </form>
</div>

</body>
</html>