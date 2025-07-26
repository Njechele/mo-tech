<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include './includes/db.php';
include 'header.php';

// Handle delete all history
if (isset($_POST['delete_all_history'])) {
    mysqli_query($conn, "DELETE FROM student_class_history");
    echo "<script>alert('All student class history has been deleted!');window.location.href='student_history.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Class History</title>
    <style>
        body {
            font-family: 'poppins';
            margin: 0;
            background: whitesmoke;
        }
        .container-history {
            margin-left: 17%;
            margin-top: 120px;
            padding: 30px 30px 40px 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            width: 80%;
            min-width: 320px;
        }
        h2 {
            color: #3b4265;
            margin-bottom: 25px;
            text-align: left;
            font-size: 28px;
            letter-spacing: 1px;
        }
        .delete-all-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            margin-top: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .delete-all-btn:hover {
            background: #c0392b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        th, td {
            border: 1px solid #aaa;
            padding: 12px 8px;
            text-align: center;
            font-size: 16px;
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
        .no-data {
            color: #d9534f;
            font-weight: bold;
            font-size: 17px;
        }
        @media (max-width: 900px) {
            .container-history {
                margin-left: 0;
                width: 98%;
                margin-top: 110px;
                padding: 10px 2px;
            }
            table, th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container-history">
        <h2><i class="fa fa-history"></i> Student Class History</h2>
        <form method="post" onsubmit="return confirm('Are you sure you want to delete ALL student class history? This action cannot be undone!');">
            <button type="submit" name="delete_all_history" class="delete-all-btn">
                <i class="fa fa-trash"></i> Delete All History
            </button>
        </form>
        <table>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Form</th>
                <th>Academic Year</th>
                <th>Date Assigned</th>
            </tr>
            <?php
            $sql = "
                SELECT 
                    u.user_id,
                    CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS full_name,
                    h.class_id,
                    h.academic_year,
                    h.date_assigned
                FROM student_class_history h
                JOIN users u ON u.user_id = h.user_id
                ORDER BY u.user_id, h.date_assigned
            ";

            $result = mysqli_query($conn, $sql);
            $count = 1;

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $count++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                    echo "<td>Form " . $row['class_id'] . "</td>";
                    echo "<td>" . $row['academic_year'] . "</td>";
                    echo "<td>" . $row['date_assigned'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-data'>No class history found.</td></tr>";
            }
            ?>
        </table>
    </div>
    <?php include "footer.php"; ?>

</body>
</html>
