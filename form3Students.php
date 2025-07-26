<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:index.php');
    exit();
}

include 'header.php';
include './includes/db.php';
include "footer.php";

// Fetch Form Three students (class_id = 3)
$sql = "SELECT user_id, first_name, middle_name, last_name, gender, stream FROM users WHERE class_id = 3 AND role = 'student'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Report Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'poppins', sans-serif; background-color: whitesmoke; }

        .result-header h2 {
            text-align: center;
            margin-top: 80px;
            background: #fff;
            box-shadow: 10px 15px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .result-container {
            margin: 20px auto;
            width: 90%;
            max-width: 1100px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3b4265;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .edit {
            background: green;
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .delete {
            background: red;
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .result-container { width: 100%; padding: 10px; }
            th, td { font-size: 13px; }
        }
    </style>
</head>
<body>
    <div class="result-header">
        <h2>FORM THREE STUDENTS</h2>
    </div>

    <div class="result-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>NAME</th>
                    <th>GENDER</th>
                    <th>STREAM</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $num = 1;
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['user_id'];
                        $first = $row['first_name'];
                        $middleInitial = strtoupper(substr($row['middle_name'], 0, 1));
                        $last = $row['last_name'];
                        $full_name = "$first $middleInitial. $last";
                        $stream = $row['stream'];

                        echo "<tr>";
                        echo "<td>$num</td>";
                        echo "<td>$full_name</td>";
                        echo "<td>" . $row['gender'] . "</td>";
                        echo "<td>$stream</td>";
                        echo "<td>
                                <a href='editStudents.php?user_id=$id&return=form3Students.php' class='edit'>Edit</a>
                                <a href='deleteStudents.php?user_id=$id&return=form3Students.php' class='delete' onclick=\"return confirm('Are you sure you want to delete?');\">Delete</a>
                              </td>";
                        echo "</tr>";
                        $num++;
                    }
                } else {
                    echo "<tr><td colspan='5' style='color: red; text-align: center;'>No students found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
