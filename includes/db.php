<?php
$host = 'localhost';
$db_user = 'root';
$db_pwd = '';
$db_name = 'secondaryschool';

$conn = mysqli_connect($host, $db_user, $db_pwd, $db_name);

// Check connection
if ($conn) {
    // echo 'connection successfully';
}else{
    'no connection';
}

?>