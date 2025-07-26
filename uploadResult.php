<?php
session_start();
include './includes/db.php';

// PHPMailer autoload
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Receive GET parameters
$user_id = $_GET['user_id'] ?? '';
$term_id = $_GET['term_id'] ?? '';
$class_id = $_GET['class_id'] ?? '';

// Check required parameters
if (!$term_id) {
    echo "<script>alert('Please select a valid term to upload result'); window.location.href='teachers.php';</script>";
    exit;
}

// Get class name
$class_query = mysqli_query($conn, "SELECT class_name FROM class WHERE class_id = '$class_id'");
$class_row = mysqli_fetch_assoc($class_query);
$class_name = strtolower(trim($class_row['class_name']));

// Get student stream
$stream_query = mysqli_query($conn, "SELECT stream FROM users WHERE user_id = '$user_id'");
$stream_row = mysqli_fetch_assoc($stream_query);
$student_stream = strtolower(trim($stream_row['stream']));

// Validate marks function
function validate_mark($mark) {
    if ($mark === '-' || $mark === '') {
        return true; // Allow dash or blank for missing subjects
    }
    return is_numeric($mark) && $mark >= 0 && $mark <= 100;
}

// Calculate point from mark
function getpoint($mark){
    if ($mark === '-' || $mark === '') {
        return 0;
    }
    $mark = (int)$mark;
    if($mark >= 75) return 1;
    elseif($mark >= 65) return 2;
    elseif($mark >= 45) return 3;
    elseif($mark >= 30) return 4;
    else return 5;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Prevent re-upload for same term
    $check_existing = mysqli_query($conn, "SELECT * FROM result WHERE user_id = '$user_id' AND term_id = '$term_id' AND class_id = '$class_id'");
    if (mysqli_num_rows($check_existing) > 0) {
        echo "<script>alert('Result already uploaded for this student in this term. Upload is only allowed once per academic year.'); window.location.href='teachers.php';</script>";
        exit;
    }

    // Receive marks
    $kiswa = trim($_POST['kiswahili']);
    $engl  = trim($_POST['english']);
    $geo   = trim($_POST['geography']);
    $civ   = trim($_POST['civics']);
    $math  = trim($_POST['mathematics']);
    $hist  = trim($_POST['history']);
    $bio   = trim($_POST['biology']);
    $chem  = ($class_name == 'form one' || $class_name == 'form two' || $student_stream == 'science') ? trim($_POST['chemistry']) : '-';
    $phy   = ($class_name == 'form one' || $class_name == 'form two' || $student_stream == 'science') ? trim($_POST['physics']) : '-';

    // Validate all marks
    $marks_to_validate = [$kiswa, $engl, $geo, $civ, $math, $hist, $bio];
    if ($chem !== '-') $marks_to_validate[] = $chem;
    if ($phy !== '-') $marks_to_validate[] = $phy;

    foreach ($marks_to_validate as $mark) {
        if (!validate_mark($mark)) {
            echo "<script>alert('All marks must be numbers between 0 and 100 or left blank/dash for missing subjects!'); window.history.back();</script>";
            exit;
        }
    }

    // Calculate total marks, points, average
    $total_marks = 0;
    $total_points = 0;
    $subject_count = 0;

    foreach ($marks_to_validate as $mark) {
        if ($mark !== '-' && $mark !== '') {
            $total_marks += (int)$mark;
            $total_points += getpoint($mark);
            $subject_count++;
        }
    }

    $average = $subject_count > 0 ? round($total_marks / $subject_count, 2) : 0;

    if ($total_points >= 7 && $total_points <= 17) {
        $division = "Division I";
        $remark = "Excellent";
    } elseif ($total_points >= 18 && $total_points <= 22) {
        $division = "Division II";
        $remark = "Very Good";
    } elseif ($total_points >= 23 && $total_points <= 25) {
        $division = "Division III";
        $remark = "Good";
    } elseif ($total_points >= 26 && $total_points <= 34) {
        $division = "Division IV";
        $remark = "Pass";
    } else {
        $division = "Division 0";
        $remark = "Fail";
    }

    // Insert result (bila is_seen_parent)
    $insert_sql = "INSERT INTO result(user_id, term_id, class_id, kiswahili, english, geography, civics, mathematics, history, biology, physics, chemistry, avarage, point, division, remark)
        VALUES('$user_id', '$term_id', '$class_id', '$kiswa', '$engl', '$geo', '$civ', '$math', '$hist', '$bio', '$phy', '$chem', '$average', '$total_points', '$division', '$remark')";
    $exec = mysqli_query($conn, $insert_sql);

    // --- TUMA EMAIL KWA MZAZI KWA PHPMailer ---
    // Tafuta email ya mzazi (mfano: parent_email kwenye users au parent table)
    $parent_query = mysqli_query($conn, "SELECT parent_email FROM users WHERE user_id = '$user_id'");
    $parent = mysqli_fetch_assoc($parent_query);
    $parent_email = $parent['parent_email'] ?? '';

    if ($exec && $parent_email) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Badilisha kama unatumia SMTP nyingine
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mohammedimohammedi200@gmail.com'; // Weka email yako
            $mail->Password   = 'vrpv uzud adjf spdy'; // Tumia app password ya Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('mohammedimohammedi200@gmail.com', 'School System');
            $mail->addAddress($parent_email);

            $mail->isHTML(true);
            $mail->Subject = 'New Student Results Available';
            $mail->Body    = 'Dear Parent,<br>Your child\'s new results have been uploaded. Please log in to the system to view or download the report.<br><br>Thank you.';

            $mail->send();
            // Optional: You can log success here
        } catch (Exception $e) {
            // Optional: Log error $mail->ErrorInfo
        }
    }

    if($exec) {
        // Pangilia position: point ASC (division nzuri juu), kama point sawa avarage DESC (kubwa juu)
        $rankQuery = "SELECT user_id, point, avarage FROM result WHERE term_id = '$term_id' AND class_id = '$class_id' ORDER BY point ASC, avarage DESC";
        $rankResult = mysqli_query($conn, $rankQuery);
        $position = 0;
        $rank = 0;
        $prev_point = null;
        $prev_avg = null;
        $total_students = mysqli_num_rows($rankResult);

        while($row = mysqli_fetch_assoc($rankResult)) {
            $rank++;
            // Ikiwa point tofauti, position inabadilika
            if ($prev_point !== $row['point']) {
                $position = $rank;
            }
            // Ikiwa point sawa, avarage tofauti, position inabadilika
            elseif ($prev_avg !== $row['avarage']) {
                $position = $rank;
            }
            $uid = $row['user_id'];
            $position_text = "Position: $position/$total_students";
            $updateQuery = "UPDATE result SET position = '$position_text' WHERE user_id = '$uid' AND term_id = '$term_id' AND class_id = '$class_id'";
            mysqli_query($conn, $updateQuery);
            $prev_point = $row['point'];
            $prev_avg = $row['avarage'];
        }

        header("Location: teachers.php");
        exit();
    } else {
        echo "Failed to save result.";
    }
}
include "header2.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Result</title>
    <style>
        body { font-family: 'poppins'; background: whitesmoke; }
        .upload-container {
            margin-top: 70px;
            background: #fff;
            box-shadow: 10px 15px 15px rgba(0,0,0,0.1);
            width: 30%; margin-left: 35%;
            border: 2px solid;
            text-align: center; padding-bottom: 20px;
        }
        .upload-container h2 { border: 1px solid; margin: 10px; padding: 5px; }
        .upload-container label {
            display: block;
            margin-top: 10px;
            margin-bottom: 2px;
            font-weight: 500;
            text-align: left;
            margin-left: 10%;
        }
        .upload-container input {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 80%;
            border: 2px solid transparent;
            background-color: rgba(0,0,0,0.1);
        }
        .upload-container input:focus { border: 2px solid blue; }
        button {
            background: blue;
            color: white;
            border: none;
            padding: 10px 50px;
            border-radius: 10px;
            transition: 0.3s ease-in-out;
            margin-bottom: 30px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="upload-container">
    <form action="" method="POST">
        <h2>UPLOAD RESULT</h2>

        <label for="kiswahili">Kiswahili</label>
        <input type="number" name="kiswahili" id="kiswahili" placeholder="Kiswahili" min="0" max="100" required><br>

        <label for="english">English</label>
        <input type="number" name="english" id="english" placeholder="English" min="0" max="100" required><br>

        <label for="history">History</label>
        <input type="number" name="history" id="history" placeholder="History" min="0" max="100" required><br>

        <label for="mathematics">Mathematics</label>
        <input type="number" name="mathematics" id="mathematics" placeholder="Mathematics" min="0" max="100" required><br>

        <label for="geography">Geography</label>
        <input type="number" name="geography" id="geography" placeholder="Geography" min="0" max="100" required><br>

        <label for="civics">Civics</label>
        <input type="number" name="civics" id="civics" placeholder="Civics" min="0" max="100" required><br>

        <label for="biology">Biology</label>
        <input type="number" name="biology" id="biology" placeholder="Biology" min="0" max="100" required><br>

        <?php if ($class_name == 'form one' || $class_name == 'form two' || $student_stream == 'science'): ?>
            <label for="chemistry">Chemistry</label>
            <input type="number" name="chemistry" id="chemistry" placeholder="Chemistry" min="0" max="100" required><br>

            <label for="physics">Physics</label>
            <input type="number" name="physics" id="physics" placeholder="Physics" min="0" max="100" required><br>
        <?php endif; ?>

        <button type="submit">Upload</button>
    </form>
</div>
<?php include "footer.php"; ?>
</body>
</html>
