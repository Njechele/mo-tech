<?php
include "includes/db.php";

$id = $_GET['userid'];

$sql = "DELETE  FROM users WHERE user_id = '$id'";
$deleted = mysqli_query($conn, $sql);

if($deleted){
    echo"<script>alert('Deleted successfully');window.location.href='viewTeachers.php'</script>";
}
    


?>