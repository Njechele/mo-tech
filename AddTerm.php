<?php
// session_start();
include './includes/db.php';
include 'header.php';
include "footer.php";


$query = "SELECT * FROM term";
$result = mysqli_query($conn, $query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $term_name = $_POST['term_name'];
    $start = $_POST['start'];
    $end = $_POST['end'];


  
$check =mysqli_query($conn, "SELECT * FROM term WHERE term_name = '$term_name'");

if(mysqli_num_rows($check) > 0){
    echo "<script>alert('$term_name Already existed.');</script>";
}else{
$sql = mysqli_query($conn, "INSERT INTO term(term_name,start_date,end_date) value('$term_name','$start','$end')");
    if($sql){
        echo "<script>alert('$term_name added seccessfully.');;window.location.href='viewTerm.php'</script>";
    }
}
}
  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>student report management system</title>
    <style>
        *{
            border: none;
    outline: none;
    box-sizing: border-box;
    margin: 0;
     padding: 0;
     text-decoration: none;

        }
body{
    font-family: 'poppins'; 
    margin: 0;
    padding: 0;
    background: whitesmoke;
        }
.container{
    background: #FFF;
    box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
    width: 500px;
    margin: 30px auto;
    margin-top: 150px;
    
}
form input, .select{
    padding: 15px 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    width: 100%;
    border: 2px solid transparent;
    background-color:  rgba(0, 0, 0, 0.1);
}
    
h2{
     padding-top: 20px;
     text-align: center;
}
button{
 
    background: blue;
    color: white;
    border: none;
    padding: 10px 50px;
    border-radius: 10px;
    transition: 0.3s ease-in-out;
    margin-left: 35%;
    margin-bottom: 10px;
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
        <h2>ADD NEW TERM</h2>
        <label>Term Name</label><br>
        <select name="term_name" required class="select">
            <option value="">..Select Term..</option>    
            <option value="	Midterm Term I">	Midterm Term I</option>
            <option value="End Term I">End Term I</option>
            <option value="Midterm Term II">Midterm Term II	</option>
            <option value="End Term II">Midterm Term II	</option>

            </select><br><br>
            <label>Start date</label><br>
            <input type="date" name="start" required><br><br>
            <label>End date</label><br>
            <input type="date" name="end" required> 
 
            <button>add Term</button>
    </div>
    </form>

</body>
</html>