<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_GET['term_id'])) {
    die('Term not specified.');
}

session_start();
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$user_id = intval($_SESSION['user_id']);
$term_id = intval($_GET['term_id']);

// Fetch result for this user and term
$sql = "SELECT r.*, u.first_name, u.middle_name, u.last_name
        FROM result r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.user_id = $user_id AND r.term_id = $term_id";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    die('No result found for this term.');
}

$row = mysqli_fetch_assoc($res);
$full_name = $row['first_name'] . ' ' . strtoupper(substr($row['middle_name'], 0, 1)) . '. ' . $row['last_name'];
$division = $row['division'];
$term_name = '';
// pata jina la term
$termQ = mysqli_query($conn, "SELECT term_name FROM term WHERE term_id = $term_id");
if ($termQ && mysqli_num_rows($termQ) > 0) {
    $termRow = mysqli_fetch_assoc($termQ);
    $term_name = $termRow['term_name'];
}

// Prepare subjects
$subjects = [
    ['Subject Name' => 'Kiswahili',   'Score' => $row['kiswahili']],
    ['Subject Name' => 'English',     'Score' => $row['english']],
    ['Subject Name' => 'History',     'Score' => $row['history']],
    ['Subject Name' => 'Mathematics', 'Score' => $row['mathematics']],
    ['Subject Name' => 'Geography',   'Score' => $row['geography']],
    ['Subject Name' => 'Civics',      'Score' => $row['civics']],
    ['Subject Name' => 'Biology',     'Score' => $row['biology']],
    ['Subject Name' => 'Chemistry',   'Score' => $row['chemistry']],
    ['Subject Name' => 'Physics',     'Score' => $row['physics']],
];

$subjectRows = '';
foreach ($subjects as $subject) {
    $subjectRows .= '<tr>
        <td>'.$subject['Subject Name'].'</td>
        <td>'.$subject['Score'].'</td>
    </tr>';
}

$reportContent = '
<style>
    body { font-family: DejaVu Sans, sans-serif; }
    .school-header { text-align: center; margin-bottom: 10px; }
    .school-header h2 { margin: 0; }
    .school-header p { margin: 0; font-size: 13px; }
    .report-title { text-align: center; font-size: 18px; margin: 20px 0 10px 0; }
    .student-info { margin-bottom: 20px; }
    .student-info td { padding: 5px 10px; }
    table.subjects { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    table.subjects th, table.subjects td { border: 1px solid #444; padding: 8px 12px; text-align: left; }
    table.subjects th { background: #eee; }
    .summary { text-align: center; font-size: 14px; margin-top: 10px; }
</style>

<div class="school-header">
    <h2>SSTUDENT REPORT</h2>
    <p>P.O. Box 123, City: Dar es salaam</p>
</div>

<div class="report-title"><b>Student Academic Report</b></div>

<table class="student-info">
    <tr>
        <td><b>Student Name:</b></td>
        <td>'.$full_name.'</td>
        <td><b>Term:</b></td>
        <td>'.$term_name.'</td>
    </tr>
    <tr>
        <td><b>Division:</b></td>
        <td>'.$division.'</td>
        <td><b>Year:</b></td>
        <td>'.date('Y').'</td>
    </tr>
</table>

<table class="subjects">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        '.$subjectRows.'
    </tbody>
</table>

<div class="summary">
    <b>Average:</b> '.$row['avarage'].' &nbsp; | &nbsp;
    <b>Points:</b> '.$row['point'].' &nbsp; | &nbsp;
    <b>Remark:</b> '.$row['remark'].' &nbsp; | &nbsp;
    <b>Position:</b> '.$row['position'].'
</div>
';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($reportContent);

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="student_report.pdf"');

$mpdf->Output('student_report.pdf', \Mpdf\Output\Destination::DOWNLOAD);
exit;