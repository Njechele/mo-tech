<?php
session_start();
include "header.php";
include "./includes/db.php";
$teacher_id = $_GET['user_id'];
include "footer.php";

$sql = "SELECT * FROM users WHERE user_id = '$teacher_id'";
$results = mysqli_query($conn, $sql);
$teacher = mysqli_fetch_array($results);

//process 

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
    $user_id = $_POST['user_id'];
    $fname = $_POST['first_name'];
    $mname = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $lname = $_POST['last_name'];
    $role = $_POST['role'];
    $gender = $_POST['gender'];
  


   

    $update = "UPDATE users SET first_name='$fname', middle_name = '$mname', last_name = '$lname', role = '$role', gender ='$gender' 
    WHERE user_id='$teacher_id'";



    if( mysqli_query($conn, $update) == TRUE ) {
        // header('Location: class.php');
        echo "<script>alert('Teacher edited successfully');window.location.href='viewTeachers.php';</script>";
    } else {
        echo "<script>alert('Edited Not successfully');window.location.href='viewTeachers.php';</script>";
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
    text-decoration: none;
}
body{
    font-family: 'poppins'
    background-color: whitesmoke;
}

.edit-container{
    background: #FFF;
    box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
    width: 400px;
    margin: 30px auto;
    margin-top: 150px;
    text-align: center;
}
h1{
    margin-top: 30px;
    
    
}
.edit-container input,select{

    padding: 15px 10px;
    border-radius: 5px;
    /* margin-top: 10px; */
    margin-bottom: 10px;
    width: 60%;
    border: 2px solid transparent;
    background-color:  rgba(0, 0, 0, 0.1);
}
button{
    
    background: blue;
    color: white;
    border: none;
    padding: 10px 50px;
    border-radius: 10px;
    transition: 0.3s ease-in-out;
    margin: 10px;
}
button:hover{
    background: rgb(8, 8, 67);
    letter-spacing: 1.3px;
}

</style>
</head>
<body>
<form action="" method="POST">


   
        <div class="edit-container">
            <h1>update teacher</h1>
            <input type="hidden" name="user_id" value="<?Php echo $teacher_id;?>">
            <label>First Name</label><br>
            <input type="text" name="first_name" class="form-control" value="<?php echo $teacher['first_name']; ?>" required><br><br>
            <label>Middle Name</label><br>
            <input type="text" name="middle_name" class="form-control" value="<?php echo $teacher['middle_name']; ?>" required><br><br>
            <label>Last Name</label><br>
            <input type="text" name="last_name" class="form-control" value="<?php echo $teacher['last_name']; ?>" required><br><br>
            <label>role</label><br>
            <select name="role" class="form-control">
                <option value="form1_teacher" <?php if($teacher['role'] == 'form1_teacher') echo 'selected' ; ?>>form1_teacher</option>
                <option value="form2_teacher" <?php if($teacher['role'] == 'form2_teacher') echo 'selected' ; ?>>form2_teacher</option>
                <option value="form3_teacher" <?php if($teacher['role'] == 'form3_teacher') echo 'selected' ; ?>>form3_teacher</option>
                <option value="form4_teacher" <?php if($teacher['role'] == 'form4_teacher') echo 'selected' ; ?>>form4_teacher</option>

            </select><br><br>
            <select name="gender" class="form-control" required>
                <option value="Male" <?php if($teacher['gender'] == 'Male') echo 'selected' ; ?>>Male</option>
                <option value="Female" <?php if($teacher['gender'] == 'Female') echo 'selected' ; ?>>Female</option>
            </select>
            <button type="submit">submit</button>
        </div>

        </form>
</body>
</html>
