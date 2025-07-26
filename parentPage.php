<?php
session_start();
if (!isset($_SESSION['class_id']) || !isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

include './includes/db.php';
include "parent_header.php";

$class_id = $_SESSION['class_id'];
$user_id = $_SESSION['user_id'];
$display_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$currentDate = date('Y-m-d');

// Handle comment submission
if (isset($_POST['submit_comment']) && isset($_POST['parent_comment'])) {
    $parent_comment = mysqli_real_escape_string($conn, $_POST['parent_comment']);
    $sql_comment = "INSERT INTO comments (user_id, comment, class_id) VALUES ($user_id, '$parent_comment', $class_id)";
    mysqli_query($conn, $sql_comment);
    echo "<script>alert('Comment submitted successfully!');</script>";
}

// Check if student is graduated
$status = '';
$graduation_year = '';
$student_query = mysqli_query($conn, "SELECT status, graduation_year FROM users WHERE user_id = $user_id");
if ($student_query && mysqli_num_rows($student_query) > 0) {
    $row = mysqli_fetch_assoc($student_query);
    $status = $row['status'];
    $graduation_year = $row['graduation_year'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Student Report Management System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<style>
    body { 
        font-family: 'poppins'; 
        background: whitesmoke; 
        margin: 0; 
        padding: 0; 
    }
    .result-header {
        margin: 60px auto 0 auto;
        padding: 25px 0 25px 0;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(215, 15, 15, 0.08);
        max-width: 600px;
        font-size: 22px;
        font-weight: bold;
        color: #333;
        text-align: center;
        letter-spacing: 1px;
    }
    .download-btn {
        display: inline-block;
        margin: 10px 0 20px 0;
        background: #3498db;
        color: #fff;
        padding: 8px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: background 0.2s;
    }
    .download-btn:hover {
        background: #217dbb;
    }
    .result-container { 
        background: #fff; 
        border-radius: 10px; 
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.08); 
        padding: 25px 10px 30px 10px; 
        margin-top: 20px; 
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
    .no-results { 
        color: #d9534f; 
        font-weight: bold; 
        font-size: 17px; 
        text-align: center; 
        padding: 10px 0; 
    }
    .term-header { 
        background: #fff; 
        padding: 10px; 
        box-shadow: 2px 2px 8px rgba(0,0,0,0.1); 
        margin-top: 30px; 
        text-align: center; 
        font-size: 18px; 
        font-weight: bold; 
        color: #222; 
        border-radius: 8px; 
    }
    .congrats-box { 
        margin: 60px auto 0 auto; 
        margin-left: 25%; 
        max-width: 700px; 
        background: #e6eaf5; 
        border-radius: 12px; 
        box-shadow: 0 2px 10px rgba(59,66,101,0.08); 
        padding: 40px 30px; 
        text-align: center; 
        color: #3b4265; 
        font-size: 22px; 
        font-weight: 600; 
        border: 2px solid #ffd700; 
    }
    .congrats-box i { 
        color: #ffd700; 
        font-size: 40px; 
        margin-bottom: 15px; 
        display: block; 
    }
    .comment-form {
        margin: 30px auto 0 auto;
        max-width: 600px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(59,66,101,0.08);
        padding: 20px;
    }
    .comment-form textarea {
        width: 100%;
        min-height: 60px;
        border-radius: 6px;
        border: 1px solid #aaa;
        padding: 8px;
        font-size: 15px;
        margin-bottom: 8px;
        resize: vertical;
    }
    .comment-form button {
        background: #3498db;
        color: #fff;
        border: none;
        padding: 8px 24px;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .comment-form button:hover {
        background: #217dbb;
    }
    /* Responsive styles for tablet and mobile */
@media (max-width: 900px) {
    .header-container p, .header-container h2 {
        position: static;
        font-size: 18px;
        margin-top: 0;
        box-shadow: none;
    }
    .nav-link {
        position: static;
        width: 100%;
        height: auto;
        flex-direction: row;
        justify-content: space-around;
        margin-top: 0;
        background: #3b4265;
        padding: 10px 0;
    }
    .nav-link a {
        font-size: 15px;
        margin: 0 5px;
        padding: 10px 8px;
    }
}
@media (max-width: 600px) {
    .header-container p, .header-container h2 {
        font-size: 15px;
    }
    .nav-link {
        flex-direction: column;
        align-items: stretch;
        padding: 0;
    }
    .nav-link a {
        font-size: 14px;
        padding: 10px 5px;
        margin: 2px 0;
    }
}
</style>
</head>
<body>
<div class="result-header">
    Welcome, <span style="color:#3b4265;"><?php echo htmlspecialchars($display_name); ?></span>
</div>

<?php if ($status === 'graduated' && !empty($graduation_year)): ?>
    <div class="congrats-box">
        <i class="fa fa-graduation-cap"></i>
        Congratulations <?php echo htmlspecialchars($display_name); ?> on completing Form Four!<br>
        <span style="font-size:17px; color:#222;">
            <?php echo "You graduated in the academic year: <b>" . htmlspecialchars($graduation_year) . "</b>"; ?>
        </span>
        <br><br>
        <span style="font-size:16px; color:#3b4265;">We wish you all the best in your next journey!</span>
    </div>
<?php else: ?>
<div class="result-container">
<?php
// Chukua terms zote zilizomalizika au ziko active (zimeanza au zimeisha, lakini si zijazo)
$sql_terms = "SELECT * FROM term WHERE start_date <= '$currentDate' ORDER BY start_date ASC";
$result_terms = mysqli_query($conn, $sql_terms);

$term_found = false;

if ($result_terms && mysqli_num_rows($result_terms) > 0) {
    while ($term = mysqli_fetch_assoc($result_terms)) {
        $start_date = $term['start_date'];
        $end_date = $term['end_date'];
        $term_id = $term['term_id'];
        $term_name = $term['term_name'];

        // Onyesha mwaka tu (mfano: 2025)
        $year_only = date('Y', strtotime($start_date));

        // Onyesha table kama term imeisha au ipo active, lakini usionyeshe kama haijafikiwa bado
        if ($start_date <= $currentDate) {
            $term_found = true;
            echo "<h2 class='term-header'><i class='fa fa-calendar-alt'></i> " . htmlspecialchars($term_name) . " (" . $year_only . ")</h2>";

            // Download button for this term
            echo "<a href='download_result.php?term_id=$term_id' target='_blank' class='download-btn'>
                    <i class='fa fa-download'></i> Download Result for " . htmlspecialchars($term_name) . "
                  </a>";

            // Query to get this student's result for the term and current class only
            $sql_result = "SELECT r.*, u.first_name, u.middle_name, u.last_name, u.class_id
                FROM result r
                JOIN users u ON r.user_id = u.user_id
                WHERE r.user_id = $user_id AND r.term_id = $term_id AND r.class_id = $class_id";
            $result_data = mysqli_query($conn, $sql_result);

            echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>FULL NAME</th>
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
                    </tr>
                </thead>
                <tbody>";

            if ($result_data && mysqli_num_rows($result_data) > 0) {
                $row = mysqli_fetch_assoc($result_data);
                $full_name = $row['first_name'] . ' ' . strtoupper(substr($row['middle_name'], 0, 1)) . '. ' . $row['last_name'];

                echo "<tr>
                    <td>" . htmlspecialchars($row['user_id']) . "</td>
                    <td>" . htmlspecialchars($full_name) . "</td>
                    <td>" . htmlspecialchars($row['kiswahili']) . "</td>
                    <td>" . htmlspecialchars($row['english']) . "</td>
                    <td>" . htmlspecialchars($row['history']) . "</td>
                    <td>" . htmlspecialchars($row['mathematics']) . "</td>
                    <td>" . htmlspecialchars($row['geography']) . "</td>
                    <td>" . htmlspecialchars($row['civics']) . "</td>
                    <td>" . htmlspecialchars($row['biology']) . "</td>
                    <td>" . htmlspecialchars($row['chemistry']) . "</td>
                    <td>" . htmlspecialchars($row['physics']) . "</td>
                    <td>" . htmlspecialchars($row['avarage']) . "</td>
                    <td>" . htmlspecialchars($row['point']) . "</td>
                    <td>" . htmlspecialchars($row['division']) . "</td>
                    <td>" . htmlspecialchars($row['remark']) . "</td>
                    <td>" . htmlspecialchars($row['position']) . "</td>
                </tr>";
            } else {
                // Show row with "No result" message
                $full_name = $_SESSION['first_name'] . ' ' . strtoupper(substr($_SESSION['last_name'], 0, 1)) . '. ' . $_SESSION['last_name'];
                echo "<tr>
                    <td>" . htmlspecialchars($user_id) . "</td>
                    <td>" . htmlspecialchars($full_name) . "</td>
                    <td colspan='14' class='no-results'>No result uploaded for this term</td>
                </tr>";
            }
            echo "</tbody></table>";
        }
    }
}

if (!$term_found) {
    echo "<div class='no-results'>No results available for your terms yet.</div>";
}
?>
<!-- SEHEMU MOJA TU YA COMMENT CHINI YA MATOKEO YOTE -->
<form method="post" class="comment-form">
    <textarea name="parent_comment" placeholder="Write your comment here..." required></textarea>
    <button type="submit" name="submit_comment"><i class="fa fa-comment"></i> Submit Comment</button>
</form>
</div>
<?php endif; ?>
</body>
</html>
