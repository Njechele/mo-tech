<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include './includes/db.php';
include 'header.php';

// Handle delete all graduated students
if (isset($_POST['delete_all'])) {
    // Futa kwanza kwenye student_class_history kama kuna foreign key
    $get_ids = mysqli_query($conn, "SELECT user_id FROM users WHERE role = 'student' AND status = 'graduated'");
    $ids = [];
    while($row = mysqli_fetch_assoc($get_ids)) {
        $ids[] = $row['user_id'];
    }
    if (!empty($ids)) {
        $ids_str = implode(',', array_map('intval', $ids));
        mysqli_query($conn, "DELETE FROM student_class_history WHERE user_id IN ($ids_str)");
        mysqli_query($conn, "DELETE FROM users WHERE user_id IN ($ids_str)");
    }
    echo "<script>alert('All graduated students have been deleted!');window.location.href='graduated_student.php';</script>";
    exit();
}

// Handle undo graduation for one student
if (isset($_POST['undo_graduation']) && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    // Rudisha status kuwa "active" na futa graduation_year
    $update = mysqli_query($conn, "UPDATE users SET status='active', graduation_year=NULL WHERE user_id=$user_id");
    if ($update) {
        echo "<script>alert('Undo graduation successful!');window.location.href='graduated_student.php';</script>";
    } else {
        echo "<script>alert('Undo failed!');window.location.href='graduated_student.php';</script>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Graduated Students</title>
    <style>
        body {
            font-family: 'poppins';
            margin: 0;
            background: whitesmoke;
        }
        .container-graduated {
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
        .undo-btn {
            background: #3498db;
            color: #fff;
            border: none;
            padding: 6px 18px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .undo-btn:hover {
            background: #217dbb;
        }
        @media (max-width: 900px) {
            .container-graduated {
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
    <div class="container-graduated">
        <h2><i class="fa fa-graduation-cap"></i> Graduated Students</h2>
        <form method="post" onsubmit="return confirm('Are you sure you want to delete ALL graduated students? This action cannot be undone!');">
            <button type="submit" name="delete_all" class="delete-all-btn">
                <i class="fa fa-trash"></i> Delete All Graduated Students
            </button>
        </form>
        <table>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>User ID</th>
                <th>Academic Year</th>
                <th>Current Status</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT user_id, first_name, middle_name, last_name, status, graduation_year FROM users WHERE role = 'student' AND status = 'graduated'";
            $result = mysqli_query($conn, $sql);
            $count = 1;

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $full_name = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
                    echo "<tr>";
                    echo "<td>" . $count++ . "</td>";
                    echo "<td>" . htmlspecialchars($full_name) . "</td>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . (!empty($row['graduation_year']) ? $row['graduation_year'] : '-') . "</td>";
                    echo "<td>" . ucfirst($row['status']) . "</td>";
                    echo "<td>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='user_id' value='" . $row['user_id'] . "'>
                            <button type='submit' name='undo_graduation' class='undo-btn' onclick=\"return confirm('Undo graduation for this student?');\">Undo</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='no-data'>No graduated students found.</td></tr>";
            }
            ?>
        </table>
    </div>
    <?php include "footer.php"; ?>

</body>
</html>
