<?php
session_start();
include "header.php";
include "./includes/db.php";
include "footer.php";


$term_id = $_GET['id'];
$sql = "SELECT * FROM term WHERE term_id = $term_id";
$results = mysqli_query($conn, $sql);
$student = mysqli_fetch_array($results);


//process 

if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
    $name = $_POST['term_name'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];


   

    $update = "UPDATE term SET term_name='$name', start_date = '$start', end_date = '$end' WHERE term_id='$term_id'";



    if( mysqli_query($conn, $update) == TRUE ) {
        // header('Location: class.php');
        echo "<script>alert('term  edited successfully');window.location.href='viewTerm.php';</script>";
    } else {
        echo "<script>alert('Edited Not successfully');window.location.href='viewTerm.php';</script>";
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
 .container input{
    padding: 15px 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    width: 80%;
    margin-left: 50px;
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
            <h1>Edit Term</h1>
            <label>Term ID</label><br>
            <input type="hidden" name="term_id" value="<?Php echo $term_id;?>">
            <input type="text" name="term_name" class="form-control" value="<?php echo $student['term_name']; ?>" required><br><br>
            <label>Start Date</label><br>
            <input type="date" name="start_date" class="form-control" value="<?php echo $student['start_date']; ?>" required><br><br>
            <label>End Date</label><br>
            <input type="date" name="end_date" class="form-control" value="<?php echo $student['end_date']; ?>" required><br><br>

            <button type="submit" >Update</button>
    </div>
            
        </form>
</body>
</html>
