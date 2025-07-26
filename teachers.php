<?php
session_start();
// Ruhusu walimu tu wa form1, form2, form3, form4
if (
    !isset($_SESSION['role']) ||
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['class_id']) ||
    !in_array($_SESSION['role'], ['form1_teacher', 'form2_teacher', 'form3_teacher', 'form4_teacher'])
) {
    header('location:index.php');
    exit();
}

include './includes/db.php';
include "header2.php";

$current_term_id = '';
$current_date = date('Y-m-d');

// Get current active term
$sql = "SELECT * FROM term WHERE start_date <= '$current_date' AND end_date >= '$current_date'";
$result_term = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result_term);

$user_id = $_SESSION['user_id'];
$class_id = $_SESSION['class_id'];

$result = null;

if ($row) {
    $current_term_id = $row['term_id'];
    $term_name = $row['term_name'];
    $term_header = "<div class='term-info'><i class='fa fa-calendar-alt'></i> $term_name</div>";

    $sql = "SELECT users.user_id, users.middle_name, users.last_name, users.class_id, users.first_name, 
    result.kiswahili, result.english, result.history, result.mathematics, result.geography, result.civics, 
    result.biology, result.chemistry, result.physics, result.avarage, result.division, result.point, 
    result.remark, result.position
    FROM users 
    LEFT JOIN result ON users.user_id = result.user_id 
        AND result.term_id = '$current_term_id'
        AND result.class_id = users.class_id
    WHERE users.role = 'student' 
    AND users.class_id = '$class_id'
    AND (users.status IS NULL OR users.status != 'graduated')";

    $result = $conn->query($sql);
} else {
    $term_header = "<div class='term-info error'>No active semester</div>";
}
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
        }
        .result-header {
            margin-left: 15%;
            margin-top: 110px;
            padding: 0 20px 40px 20px;
        }
        .term-info {
            display: inline-block;
            background: #fff;
            color: #3b4265;
            font-size: 20px;
            font-weight: 600;
            padding: 8px 25px;
            border-radius: 8px 8px 0 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            margin-bottom: -5px;
        }
        .term-info.error {
            color: #d9534f;
            background: #ffe0e0;
            font-size: 18px;
            font-weight: bold;
            box-shadow: none;
        }
        .result-container {
            background: #fff;
            border-radius: 0 0 10px 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.08);
            padding: 25px 10px 30px 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
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
        .upload {
            background: #ffd700;
            padding: 5px 12px;
            color: #222;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: background 0.2s;
        }
        .upload:hover {
            background: #ffe066;
            color: #111;
        }
        @media (max-width: 900px) {
            .result-header {
                margin-left: 0;
                margin-top: 90px;
                padding: 0 2px;
            }
            .term-info {
                font-size: 16px;
                padding: 6px 10px;
            }
            .result-container {
                padding: 5px 2px 15px 2px;
            }
            table, th, td {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="result-header">
        <?php echo $term_header; ?>
        <div class="result-container">
            <?php if ($result !== null): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
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
                        $students = [];
                        while ($row = mysqli_fetch_assoc($result)) {
                            $students[] = $row;
                        }

                        // Panga wanafunzi kwa position: mdogo juu kabisa
                        usort($students, function($a, $b) {
                            $posA = isset($a['position']) ? intval(preg_replace('/[^0-9]/', '', explode('/', $a['position'])[0])) : PHP_INT_MAX;
                            $posB = isset($b['position']) ? intval(preg_replace('/[^0-9]/', '', explode('/', $b['position'])[0])) : PHP_INT_MAX;
                            return $posA - $posB;
                        });

                        $hasStudent = false;
                        $num = 1;
                        foreach ($students as $row) {
                            $hasStudent = true;
                            $first = $row['first_name'];
                            $middleInitial = strtoupper(substr($row['middle_name'], 0, 1));
                            $last = $row['last_name'];
                            $full_name = "$first $middleInitial. $last";

                            echo "<tr>";
                            echo "<td>$num</td>";
                            echo "<td>$full_name</td>";
                            echo "<td>" . ($row['kiswahili'] ?? '') . "</td>";
                            echo "<td>" . ($row['english'] ?? '') . "</td>";
                            echo "<td>" . ($row['history'] ?? '') . "</td>";
                            echo "<td>" . ($row['mathematics'] ?? '') . "</td>";
                            echo "<td>" . ($row['geography'] ?? '') . "</td>";
                            echo "<td>" . ($row['civics'] ?? '') . "</td>";
                            echo "<td>" . ($row['biology'] ?? '') . "</td>";
                            echo "<td>" . ($row['chemistry'] ?? '') . "</td>";
                            echo "<td>" . ($row['physics'] ?? '') . "</td>";
                            echo "<td>" . ($row['avarage'] ?? '') . "</td>";
                            echo "<td>" . ($row['point'] ?? '') . "</td>";
                            echo "<td>" . ($row['division'] ?? '') . "</td>";
                            echo "<td>" . ($row['remark'] ?? '') . "</td>";
                            echo "<td>" . ($row['position'] ?? '') . "</td>";
                            echo "<td><a class='upload' href='uploadResult.php?user_id=" . $row['user_id'] . "&class_id=" . $row['class_id'] . "&term_id=$current_term_id'>Upload</a></td>";
                            echo "</tr>";
                            $num++;
                        }

                        if (!$hasStudent) {
                            echo "<tr><td colspan='17' style='color:red;'>No students found for this class.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="error">No students found for this class.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>
