<?php
include "./includes/db.php";
include "header.php";
include "footer.php";

$sql = "SELECT * FROM term";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Report Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'poppins', sans-serif; background-color: #f4f4f4; }

        .term-header {
            margin-top: 90px;
            margin-left: 21%;
            padding: 10px;
        }

        .term-header a {
            text-decoration: none;
            background-color: blue;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 16px;
        }

        .term-table-container {
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

        .delete {
            background: red;
            padding: 6px 12px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .edit {
            background: green;
            padding: 6px 12px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
        }

        .no-term {
            color: red;
            text-align: center;
            padding: 20px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .term-header, .term-table-container {
                margin-left: 5%;
                width: 90%;
            }

            table, th, td {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

    <div class="term-header">
        <a href="AddTerm.php">+ Add New Term</a>
    </div>

    <div class="term-table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>TERM NAME</th>
                    <th>START DATE</th>
                    <th>END DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $count . "</td>";
                        echo "<td>" . htmlspecialchars($row['term_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                        echo "<td>
                            <a href='editTerm.php?id=" . $row['term_id'] . "' class='edit'>Edit</a>
                            <a href='deleteTerm.php?id=" . $row['term_id'] . "' class='delete' onclick=\"return confirm('Are you sure you want to delete this term?');\">Delete</a>
                        </td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='5' class='no-term'>No Term available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>
