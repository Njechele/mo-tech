<?php
session_start();
include './includes/db.php';
include "footer.php";
include "header2.php";

// Pokea GET parameters
$user_id = $_GET['user_id'] ?? '';
$term_id = $_GET['term_id'] ?? '';
$class_id = $_GET['class_id'] ?? '';

if (!$user_id || !$term_id || !$class_id) {
    echo "Missing required parameters.";
    exit;
}

// Tafuta result_id
$get_result_id_query = mysqli_query($conn, "SELECT result_id FROM result WHERE user_id = '$user_id' AND class_id = '$class_id' AND term_id = '$term_id' LIMIT 1");

if (mysqli_num_rows($get_result_id_query) > 0) {
    $row = mysqli_fetch_assoc($get_result_id_query);
    $result_id = $row['result_id'];
} else {
    echo "Matokeo ya mwanafunzi huyu hayajapatikana.";
    exit;
}

// Tafuta jina la darasa
$class_query = mysqli_query($conn, "SELECT class_name FROM class WHERE class_id = '$class_id'");
$class_row = mysqli_fetch_assoc($class_query);
$class_name = strtolower(trim($class_row['class_name']));

// Tafuta stream ya mwanafunzi
$stream_query = mysqli_query($conn, "SELECT stream FROM users WHERE user_id = '$user_id'");
$stream_row = mysqli_fetch_assoc($stream_query);
$student_stream = strtolower(trim($stream_row['stream']));

// Pata matokeo ya mwanafunzi
$sql = "SELECT * FROM result WHERE result_id = '$result_id'";
$result = mysqli_query($conn, $sql);
$subjects = mysqli_fetch_assoc($result);

// Tafuta total ya wanafunzi wa darasa hilo na term hiyo
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM result WHERE class_id = '$class_id' AND term_id = '$term_id'");
$total_row = mysqli_fetch_assoc($total_query);
$total_students = $total_row['total'];

// Tafuta position ya mwanafunzi huyu
$position_query = mysqli_query($conn, "SELECT position FROM result WHERE result_id = '$result_id'");
$pos_row = mysqli_fetch_assoc($position_query);
$current_position = $pos_row['position'];

// Tafuta email ya mzazi na jina la mwanafunzi
$parent_query = mysqli_query($conn, "SELECT parent_email, first_name, last_name FROM users WHERE user_id = '$user_id'");
$parent_row = mysqli_fetch_assoc($parent_query);
$parent_email = $parent_row['parent_email'];
$student_name = $parent_row['first_name'] . ' ' . $parent_row['last_name'];

// Fomula ya point
function getpoint($mark){
    if($mark >= 75) return 1;
    elseif($mark >= 65) return 2;
    elseif($mark >= 45) return 3;
    elseif($mark >= 30) return 4;
    else return 5;
}

// Process update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kiswa = $_POST['kiswahili'];
    $engl  = $_POST['english'];
    $geo   = $_POST['geography'];
    $civ   = $_POST['civics'];
    $math  = $_POST['mathematics'];
    $hist  = $_POST['history'];
    $bio   = $_POST['biology'];
    $chem  = $_POST['chemistry'] ?? '-';
    $phy   = $_POST['physics'] ?? '-';

    $include_all_subjects = ($class_name == 'form one' || $class_name == 'form two' || $student_stream == 'science');
    $chem_value = $include_all_subjects ? (is_numeric($chem) ? $chem : 0) : 0;
    $phy_value = $include_all_subjects ? (is_numeric($phy) ? $phy : 0) : 0;

    $subjects_arr = [
        'kiswahili' => $kiswa,
        'english' => $engl,
        'geography' => $geo,
        'civics' => $civ,
        'mathematics' => $math,
        'history' => $hist,
        'biology' => $bio,
        'chemistry' => $chem_value,
        'physics' => $phy_value
    ];

    $total_marks = 0;
    $total_points = 0;
    $subject_count = 0;

    foreach ($subjects_arr as $mark) {
        if (is_numeric($mark)) {
            $total_marks += $mark;
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

    // Update matokeo
    $update_sql = "UPDATE result SET 
        kiswahili='$kiswa', english='$engl', geography='$geo', civics='$civ',
        mathematics='$math', history='$hist', biology='$bio',
        chemistry='$chem', physics='$phy',
        avarage='$average', point='$total_points',
        division='$division', remark='$remark'
        WHERE result_id='$result_id'";

    if (mysqli_query($conn, $update_sql)) {
        // Pangilia nafasi upya baada ya update na iandike kama "namba/total"
        $rank_sql = "SELECT result_id, avarage FROM result WHERE class_id = '$class_id' AND term_id = '$term_id' ORDER BY avarage DESC";
        $rank_result = mysqli_query($conn, $rank_sql);
        $position = 1;
        while ($row = mysqli_fetch_assoc($rank_result)) {
            $rid = $row['result_id'];
            $position_str = "Position: $position/$total_students";
            mysqli_query($conn, "UPDATE result SET position = '$position_str' WHERE result_id = '$rid'");
            $position++;
        }

        // Tuma notification kwa mzazi kama email ipo na format ni sahihi
        if (!empty($parent_email) && filter_var($parent_email, FILTER_VALIDATE_EMAIL)) {
            $subject = "Matokeo ya mwanafunzi yamebadilishwa";
            $message = "Habari, matokeo ya mwanafunzi $student_name yamebadilishwa na sasa yanapatikana kwenye mfumo. Tafadhali tembelea akaunti yako kuona matokeo mapya.";
            $headers = "From: no-reply@schooldomain.com\r\n";
            @mail($parent_email, $subject, $message, $headers);
        }

        echo "<script>alert('Result edited successfully'); window.location.href='teachers.php';</script>";
        exit;
    } else {
        echo "Form not processed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Result</title>
    <style>
        body { 
            font-family: 'poppins';
            background: whitesmoke; 
        }
        .result-container {
            margin-top: 70px;
            background: #fff;
            box-shadow: 10px 15px 15px rgba(0, 0, 0, 0.1);
            width: 30%; margin-left: 35%;
            border: 2px solid;
            text-align: center; padding-bottom: 10px;
        }
        .result-container h2 { border: 1px solid; margin: 10px; padding: 5px; }
        .result-container label {
            display: block;
            margin-top: 10px;
            margin-bottom: 2px;
            font-weight: 500;
            text-align: left;
            margin-left: 10%;
        }
        .result-container input {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 7px;
            width: 80%;
            border: 2px solid transparent;
            background-color: rgba(0, 0, 0, 0.1);
        }
        .result-container input:focus { border: 2px solid blue; }
        button {
            background: blue;
            color: white;
            border: none;
            padding: 10px 50px;
            border-radius: 10px;
            transition: 0.3s ease-in-out;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="result-container">
    <form action="" method="POST">
        <h2>EDIT RESULT</h2>
        <p><strong>Position:</strong> <?php echo $current_position; ?></p>

        <label for="kiswahili">Kiswahili</label>
        <input type="text" name="kiswahili" id="kiswahili" value="<?php echo $subjects['kiswahili']; ?>" placeholder="Kiswahili" required><br>

        <label for="english">English</label>
        <input type="text" name="english" id="english" value="<?php echo $subjects['english']; ?>" placeholder="English" required><br>

        <label for="history">History</label>
        <input type="text" name="history" id="history" value="<?php echo $subjects['history']; ?>" placeholder="History" required><br>

        <label for="mathematics">Mathematics</label>
        <input type="text" name="mathematics" id="mathematics" value="<?php echo $subjects['mathematics']; ?>" placeholder="Mathematics" required><br>

        <label for="geography">Geography</label>
        <input type="text" name="geography" id="geography" value="<?php echo $subjects['geography']; ?>" placeholder="Geography" required><br>

        <label for="civics">Civics</label>
        <input type="text" name="civics" id="civics" value="<?php echo $subjects['civics']; ?>" placeholder="Civics" required><br>

        <label for="biology">Biology</label>
        <input type="text" name="biology" id="biology" value="<?php echo $subjects['biology']; ?>" placeholder="Biology" required><br>

        <?php if ($class_name == 'form one' || $class_name == 'form two' || $student_stream == 'science'): ?>
            <label for="chemistry">Chemistry</label>
            <input type="text" name="chemistry" id="chemistry" value="<?php echo $subjects['chemistry']; ?>" placeholder="Chemistry" required><br>

            <label for="physics">Physics</label>
            <input type="text" name="physics" id="physics" value="<?php echo $subjects['physics']; ?>" placeholder="Physics" required><br>
        <?php endif; ?>

        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>
