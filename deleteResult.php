<?php
session_start();
include "includes/db.php";

// Pokea kutoka GET kwanza
$term_id = $_GET['term_id'] ?? '';
$user_id = $_GET['user_id'] ?? '';
$class_id = $_GET['class_id'] ?? '';

// Hakiki kama kuna data
if (!$class_id || !$user_id || !$term_id) {
    echo "<script>alert('Missing required data!'); window.location.href='teachers.php';</script>";
    exit;
}

// Futa kutoka result table
$sql = "DELETE FROM result WHERE class_id = '$class_id' AND user_id = '$user_id' AND term_id = '$term_id'";
$deleted = mysqli_query($conn, $sql);

// Kama imefanikiwa kufuta, update positions upya
if ($deleted && mysqli_affected_rows($conn) > 0) {
    // Chukua wanafunzi waliobaki kwenye term na class hii, panga kwa average
    $rankQuery = "SELECT result_id FROM result WHERE term_id = '$term_id' AND class_id = '$class_id' ORDER BY avarage DESC";
    $rankResult = mysqli_query($conn, $rankQuery);
    $position = 1;
    $total_students = mysqli_num_rows($rankResult);

    while($row = mysqli_fetch_assoc($rankResult)) {
        $rid = $row['result_id'];
        $position_text = "Position: $position/$total_students";
        mysqli_query($conn, "UPDATE result SET position = '$position_text' WHERE result_id = '$rid'");
        $position++;
    }

    echo "<script>alert('Deleted successfully and positions updated.'); window.location.href='teachers.php';</script>";
} else {
    echo "<script>alert('No matching record to delete or delete failed!'); window.location.href='teachers.php';</script>";
}
?>
