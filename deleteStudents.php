<?php
// session_abort();
include "includes/db.php";
if(isset($_GET['user_id'])){


$user_id = $_GET['user_id'];
// $level = $_GET['level'];
$retunPage = $_GET['return'];

// }else{
    $delete =mysqli_query($conn, "DELETE FROM users WHERE user_id ='$user_id'");
    if($delete){
        echo "<script>alert('class deleted successfully');window.location.href='$retunPage';</script>";
   }else{
    echo "<script>alert('deleted Not successfully');window.location.href='$retunPage';</script>";

   }
}
// }


?>