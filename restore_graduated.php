<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location:index.php');
    exit();
}

include './includes/db.php';

$year = date('Y');
$last_academic_year = ($year - 1) . "/" . $year;

mysqli_begin_transaction($conn);

try {
    // Chukua graduates wa academic year iliyopita tu
    $sql = "SELECT user_id FROM users WHERE status = 'graduated' AND graduation_year = '$last_academic_year'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        throw new Exception("Failed to fetch graduated students: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];

        // Rudisha status kuwa NULL, class_id kuwa 4, na futa graduation_year
        $update = "UPDATE users SET status = NULL, class_id = 4, graduation_year = NULL WHERE user_id = $user_id";
        if (!mysqli_query($conn, $update)) {
            throw new Exception("Restore failed for user_id $user_id: " . mysqli_error($conn));
        }
    }

    mysqli_commit($conn);
    echo "<script>alert('Graduated students of $last_academic_year restored to Form 4 successfully!'); window.location.href='adminPage.php';</script>";

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Restore failed: " . addslashes($e->getMessage()) . "'); window.location.href='adminPage.php';</script>";
}
?>
