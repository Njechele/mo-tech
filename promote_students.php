<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location:index.php');
    exit();
}

include './includes/db.php';

$today = date('Y-m-d');
$year = date('Y');
$academic_year = ($year - 1) . "/" . $year;

mysqli_begin_transaction($conn);

try {
    $query = "SELECT user_id, class_id FROM users WHERE role = 'student' AND (status IS NULL OR status != 'graduated') AND class_id IN (1,2,3,4)";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Select Query Failed: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $current_class_id = $row['class_id'];

        $insert_history = "INSERT INTO student_class_history (user_id, class_id, academic_year, date_assigned)
                           VALUES ($user_id, $current_class_id, '$academic_year', '$today')";
        if (!mysqli_query($conn, $insert_history)) {
            throw new Exception("Insert History Failed for user_id $user_id: " . mysqli_error($conn));
        }

        // Promote or graduate
        if ($current_class_id == 1) {
            if (!mysqli_query($conn, "UPDATE users SET class_id = 2 WHERE user_id = $user_id")) {
                throw new Exception("Promotion to Form 2 failed for user_id $user_id: " . mysqli_error($conn));
            }
        } elseif ($current_class_id == 2) {
            if (!mysqli_query($conn, "UPDATE users SET class_id = 3 WHERE user_id = $user_id")) {
                throw new Exception("Promotion to Form 3 failed for user_id $user_id: " . mysqli_error($conn));
            }
        } elseif ($current_class_id == 3) {
            if (!mysqli_query($conn, "UPDATE users SET class_id = 4 WHERE user_id = $user_id")) {
                throw new Exception("Promotion to Form 4 failed for user_id $user_id: " . mysqli_error($conn));
            }
        } elseif ($current_class_id == 4) {
            // Graduate and set graduation_year
            if (!mysqli_query($conn, "UPDATE users SET status = 'graduated', graduation_year = '$academic_year' WHERE user_id = $user_id")) {
                throw new Exception("Graduation update failed for user_id $user_id: " . mysqli_error($conn));
            }
        }
    }

    mysqli_commit($conn);
    echo "<script>alert('Promotion done successfully!'); window.location.href='adminPage.php';</script>";

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Promotion failed: " . addslashes($e->getMessage()) . "'); window.location.href='adminPage.php';</script>";
}
?>
