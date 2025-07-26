<?php 
include './includes/db.php';
include "header.php";
include "footer.php";

// fetch teachers
$sql = "SELECT users.user_id, users.first_name, users.middle_name, users.last_name, users.gender FROM users WHERE role IN('form1_teacher', 'form2_teacher','form3_teacher','form4_teacher')";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers List</title>
    <style>
*{
    margin: 0;
    padding: 0;
    text-decoration: none;
    box-sizing: border-box;
}
body{
         font-family: 'poppins';
}
table{
    margin-left: 290px;
    margin-top: 120px;
    width: 70%;
    border-collapse: collapse;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    overflow: hidden;
    position: fixed;
}
th, td{
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
th{
    background-color: #f2f2f2;
}
tr:nth-child(even){
    background-color: #f9f9f9;
}
.delete{
    background: red;
    padding: 5px;
    color: white;
    margin: 15px;
    border-radius: 5px;
    text-decoration: none;   
}
.edit{
    background: green;
    padding: 5px;
    color: white;
    border-radius: 5px;
    text-decoration: none;
}
h2{
    margin-top: 78px;
    margin-left: 300px;
    position: fixed;
}
.teacher{
    margin-top: 101px;
    margin-left: 300px;
    position: absolute;
}
</style>
</head>
<body>
<h2>TEACHERS</h2>

 <table border>
        <thead>
            <tr>
                <th>#</th>
                <th>NAME</th>                
                <th>GENDER</th>    
                <th>ACTION</th>    
            </tr>
        </thead>
        <tbody>
        <?php
        if(mysqli_num_rows($result) > 0 ){
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)){
                $id = $row['user_id'];
                $first = $row['first_name'];
                $middleInitial = strtoupper(substr($row['middle_name'], 0, 1)); //first letter
                $last = $row['last_name'];
                $full_name = "$first $middleInitial $last";
                echo "<tr>";
                echo "<td>$count</td>";
                echo "<td>$full_name</td>";
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" .
                    "<a href='deleteTeacher.php?userid=" . $row['user_id'] . "' class='delete'>Delete</a>" .
                    "<a href='editTeacher.php?user_id=" . $row['user_id'] . "' class='edit'>Edit</a> " .
                    "</td>";
                echo "</tr>";
                $count++;
            }
        }else{
            echo "<tr><td colspan='4' class='teacher'>No teacher available</td></tr>";
        }
        ?>
        </tbody>
 </table>
</body>
</html>
