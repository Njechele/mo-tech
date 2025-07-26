<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location: index.php');
    exit();
}

include './includes/db.php';

$today = date('Y-m-d');
$currentYear = date('Y');
$academic_year = ($currentYear - 1) . "/" . $currentYear;

// Anza transaction
mysqli_begin_transaction($conn);

try {
    // Chukua historia ya wanafunzi kwa academic year ya mwisho tu
    $query = "SELECT user_id, class_id FROM student_class_history WHERE academic_year = '$academic_year'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 0) {
        throw new Exception("No promotion history found for $academic_year");
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $previous_class_id = $row['class_id'];

        // Rudisha mwanafunzi kwenye darasa lake la awali
        mysqli_query($conn, "UPDATE users SET class_id = $previous_class_id WHERE user_id = $user_id");
    }

    // Futa historia ya mwaka huu baada ya ku-undo promotion
    mysqli_query($conn, "DELETE FROM student_class_history WHERE academic_year = '$academic_year'");

    mysqli_commit($conn);
    echo "<script>alert('Undo Promotion successful. Students have been reverted.'); window.location.href='adminPage.php';</script>";

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Undo failed: " . $e->getMessage() . "'); window.location.href='adminPage.php';</script>";
}
?>
