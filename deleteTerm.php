<?php
include "includes/db.php";
$term_id = $_GET['id'];
$chechresult = mysqli_query($conn, "SELECT * FROM result WHERE term_id = $term_id");
if(mysqli_num_rows($chechresult) > 0){
    echo "<script>alert('You cant delete this term Because it has related in another page!');window.location.href='viewTerm.php'</script>";

}else{
    $delete = " DELETE FROM term WHERE term_id = $term_id";
    if(mysqli_query($conn, $delete)){
        echo "<script>alert('deletede seccessfully.');window.location.href='viewTerm.php'</script>";

   }
}



?>