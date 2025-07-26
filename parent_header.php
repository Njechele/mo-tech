<?php
include './includes/db.php';
$class_id = $_SESSION['class_id'] ?? null;

if($class_id == 1){
    $header = 'FORM ONE RESULT';
}elseif($class_id == 2){
    $header = 'FORM TWO RESULT';
}elseif($class_id == 3){
    $header = 'FORM THREE RESULT';
}elseif($class_id == 4){
    $header = 'FORM FOUR RESULT';
}else{
    $header = 'Unknown class';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
       *{
            margin: 0;
            padding: 0;
            border: none;
            outline: none;
        }
        body{
            font-family: 'poppins';
        }
        .header-container p{
            position: fixed;
            padding: 15px;
            top: 0;
            left: 0;
            width: 100%;
            background: #fff;
            box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .header-container h2{
            text-align: center;
            margin-top: 50px;
            background: #fff;
            box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
            width: 100%;
            top: 0;
            position: fixed;
        }
        .nav-link{
            display: flex;
            flex-direction: column;
            position: fixed;
            margin-top: 50px;
            top: 0;
            background: rgba(61, 46, 46, 0.801);
            width: 15%; 
            height: 100vh;   
        }
        .nav-link a{
            font-size: 17px;
            padding: 14px 20px;
            margin: 10px 5px;  
            text-decoration: none;
            color: white;
            transition: background 0.4s ease-in-out;
            position: relative;
            display: flex;
            align-items: center;
        }
        .nav-link a:hover{
            background: rgb(59, 66, 101);
        }
        .nav-link a i{
            margin-right: 10px;
            font-size: 20px;
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
    <div class="header-container">
        <p>STUDENT REPORT MANAGEMENT SYSTEM</p>
        <h2><?php echo $header; ?></h2>
    </div>
    <div class="nav-link">
        <a href="ParentPage.php"><i class="fa fa-home"></i> Dashboard</a>     
        <a href="parent_profile.php"><i class="fa fa-user"></i> Profile</a>
        <a href="parentChat.php"><i class="fa fa-comments"></i> View Comments</a>
        <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
    </div>
</body>
</html>