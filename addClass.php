<?php
include './includes/db.php';
include 'header.php';
include "footer.php";

// $class_id = $_GET['class_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class = $_POST['class_name'];

$sql = "INSERT INTO class(class_name) value('$class')";
     if( mysqli_query($conn, $sql)) {
        header('Location:class.php');
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
    font-family: 'poppins'; 
    text-decoration: none;

}
body{
    background: whitesmoke;
}
.register{
    clear: auto;
    margin-top: 100px;
    background: #fff;
    box-shadow: 10px 15px 15px rgba(0, 0, 0, 0.1);
    width: 30%;
    margin-left: 35%;
    height: 35vh;
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

background: blue;
color: white;
border: none;
padding: 10px 50px;
border-radius: 10px;
transition: 0.3s ease-in-out;
}
.register .submit:hover{
background: rgb(8, 8, 67);
letter-spacing: 0.3px;
}
.register a{
   
    
}
/* Responsive styles for tablet and mobile */
@media (max-width: 900px) {
    .header-container p, .header-container h2 {
        position: static;
        font-size: 18px;
        margin-top: 0;
        box-shadow: none;
    }
    .nav-link {
        position: static;
        width: 100%;
        height: auto;
        flex-direction: row;
        justify-content: space-around;
        margin-top: 0;
        background: #3b4265;
        padding: 10px 0;
    }
    .nav-link a {
        font-size: 15px;
        margin: 0 5px;
        padding: 10px 8px;
    }
}
@media (max-width: 600px) {
    .header-container p, .header-container h2 {
        font-size: 15px;
    }
    .nav-link {
        flex-direction: column;
        align-items: stretch;
        padding: 0;
    }
    .nav-link a {
        font-size: 14px;
        padding: 10px 5px;
        margin: 2px 0;
    }
}

    </style>

</head>
<body>
    
        <div class="register">
        <form action="" method="POST">
            <a href="class.php">VIEW CLASS</a>
            <h2>ADD CLASS</h2>
            <label>select class</label>
            <select name="class_name" style="border: 2px solid;">
                <option>form one</option>
                <option>form two</option>
                <option>form three</option>
                <option>form four</option>

            </select><br>
           
            <button type="submit" class="submit">add</button>
        </form>
</div>

</body>
</html>

