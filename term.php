<?php
session_start();
include "./includes/db.php";
include "header2.php";
include "footer.php";

if(!isset($_SESSION['user_id']) || !isset($_SESSION['class_id'])){
    header("location: index.php");
    exit();    
}
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$class_id = $_SESSION['class_id'];

$term_id = isset($_GET['term_id']) ? $_GET['term_id']: null;
$current_year = date('Y');

// Query: Leta matokeo ya darasa la mwalimu huyu, term husika na mwaka huu pekee, yakiwa yamepangwa kwa position
$sql = "SELECT users.user_id, users.middle_name, users.last_name, users.first_name, result.kiswahili, result.english, result.history,
result.mathematics, result.geography, result.civics, result.biology, result.chemistry, result.physics, result.avarage,
result.division, result.remark, result.position, result.point
FROM users 
INNER JOIN result ON users.user_id = result.user_id 
WHERE result.class_id = '$class_id' 
AND result.term_id = '$term_id'
AND YEAR(result.date_created) = '$current_year'
ORDER BY result.position ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Report Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body {
            font-family: 'poppins';
            background: whitesmoke;
            margin: 0;
            padding: 0;
        }
        .result-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.08);
            padding: 25px 10px 30px 10px;
            margin-top: 60px;
            margin-left: 15%;
            max-width: 1200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 10px 6px;
            text-align: center;
            font-size: 15px;
        }
        th {
            background-color: #3b4265;
            color: #fff;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f7f7f7;
        }
        tr:hover {
            background-color: #e6eaf5;
        }
        .action-btns {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .edit {
            background: #28a745;
            padding: 6px 16px;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 15px;
            transition: background 0.2s;
            border: none;
        }
        .edit:hover {
            background: #218838;
        }
        .delete {
            background: #d9534f;
            padding: 6px 16px;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 15px;
            transition: background 0.2s;
            border: none;
        }
        .delete:hover {
            background: #c9302c;
        }
        .no-results {
            color: #d9534f;
            font-weight: bold;
            font-size: 17px;
            text-align: center;
            padding: 10px 0;
        }
        @media (max-width: 900px) {
            .result-container {
                margin-left: 0;
                padding: 5px 2px 15px 2px;
            }
            table, th, td {
                font-size: 13px;
            }
            .action-btns {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="result-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>NAME</th>
                    <th>KISWAHILI</th>
                    <th>ENGLISH</th>
                    <th>HISTORY</th>
                    <th>MATH</th>
                    <th>GEO</th>
                    <th>CIV</th>
                    <th>BIO</th>
                    <th>CHEM</th>
                    <th>PHYS</th>
                    <th>AVERAGE</th>
                    <th>POINT</th>
                    <th>DIVISION</th>
                    <th>REMARK</th>
                    <th>POSITION</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(mysqli_num_rows($result) > 0) {
                $num = 1;
                while($row = mysqli_fetch_assoc($result)){
                    $id = $row['user_id'];
                    $first = $row['first_name'];
                    $middleInitial = strtoupper(substr($row['middle_name'], 0, 1));
                    $last = $row['last_name'];
                    $full_name = "$first $middleInitial. $last";
                    echo "<tr>";
                    echo "<td>$num</td>";
                    echo "<td>$full_name</td>";
                    echo "<td>" . $row['kiswahili'] . "</td>";
                    echo "<td>" . $row['english'] . "</td>";
                    echo "<td>" . $row['history'] . "</td>";
                    echo "<td>" . $row['mathematics'] . "</td>";
                    echo "<td>" . $row['geography'] . "</td>";
                    echo "<td>" . $row['civics'] . "</td>";
                    echo "<td>" . $row['biology'] . "</td>";
                    echo "<td>" . $row['chemistry'] . "</td>";
                    echo "<td>" . $row['physics'] . "</td>";
                    echo "<td>" . $row['avarage'] . "</td>";
                    echo "<td>" . $row['point'] . "</td>";
                    echo "<td>" . $row['division'] . "</td>";
                    echo "<td>" . $row['remark'] . "</td>";
                    echo "<td>" . $row['position'] . "</td>";
                    echo "<td>
                        <div class='action-btns'>
                            <a href='editResult.php?user_id=$id&term_id=$term_id&class_id=$class_id' class='edit'>Edit</a>
                            <a href='deleteResult.php?user_id=$id&term_id=$term_id&class_id=$class_id' class='delete' onclick=\"return confirm('Are you sure want to delete');\">Delete</a>
                        </div>
                    </td>";
                    echo "</tr>";
                    $num++;
                }
            } else {
                echo "<tr><td colspan='17' class='no-results'>No Results on This Term</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
