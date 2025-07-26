<?php
session_start();
if ($_SESSION['role'] !== 'admin' ) {
    header("Location: index.php");
    exit();
}
include 'header.php';
include "footer.php";
// $class_id = $_GET['id'];

$currentyear = date('Y');
$lastyear = $currentyear - 1;

$sql = "select user_id from users"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Report Management System</title>
    <link rel="stylesheet" href="stylee.css" />
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
    *{
        margin: 0;
        padding: 0;
        border: none;
        outline: none;
    }
    body{
        font-family: poppins, Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background: #f7f7f7;
    }
    .admin-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 90px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .admin-actions form {
        display: inline-block;
    }
    .admin-actions button {
        padding: 12px 20px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s;
        margin-bottom: 5px;
    }
    .admin-actions .promote { background: green; color: #fff; }
    .admin-actions .undo { background: #d9534f; color: #fff; }
    .admin-actions .restore { background: orange; color: #fff; }
    .admin-actions button:hover { opacity: 0.85; }

    .year{
        color: black;
        background: transparent;
        text-align: right;
        width: 100%;
        margin-top: 30px;
        margin-bottom: 10px;
    }
    .year p{
        margin: 10px;
        margin-right: 20px;
    }
    .container{
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px 60px;
        justify-content: center;
        align-items: center;
        margin: 0 auto 40px auto;
        max-width: 700px;
        min-height: 60vh;
    }
    .box{
        text-align: center;
        width: 100%;
        min-width: 220px;
        max-width: 320px;
        padding: 40px 10px 30px 10px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        transition: transform 0.2s;
        background: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .box[style*="red"] { background: red !important; }
    .box[style*="green"] { background: green !important; }
    .box[style*="rgb(76, 167, 220)"] { background: rgb(76, 167, 220) !important; }
    .box[style*="yellow"] { background: yellow !important; }
    .box:hover {
        transform: translateY(-5px) scale(1.03);
        box-shadow: 0 6px 20px rgba(0,0,0,0.13);
    }
    .icon-student {
        font-size: 70px;
        margin-bottom: 18px;
        color: #fff;
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(0,0,0,0.15);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .box[style*="red"] .icon-student { background: #e74c3c; }
    .box[style*="green"] .icon-student { background: #28a745; }
    .box[style*="rgb(76, 167, 220)"] .icon-student { background: #3498db; }
    .box[style*="yellow"] .icon-student { background: #f1c40f; color: #b38b00; }
    .box a{
        color: black;
        text-decoration: none;
        font-size: 20px;
        font-weight: 600;
        display: block;
        margin-top: 10px;
    }
    @media (max-width: 900px) {
        .container {
            grid-template-columns: 1fr;
            max-width: 400px;
        }
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
    <div class="admin-actions">
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <form method="post" action="promote_students.php" onsubmit="return confirm('Are you sure you want to promote all students?')">
                <button type="submit" class="promote">Promote Students to Next Form</button>
            </form>
            <form method="post" action="undo_promote.php" onsubmit="return confirm('Are you sure you want to revert the last promotion?')">
                <button type="submit" class="undo">Undo Last Promotion</button>
            </form>
        <?php endif; ?>
        <form action="restore_graduated.php" method="post" onsubmit="return confirm('Are you sure you want to restore all graduated students?')">
            <button type="submit" class="restore">Restore Graduated Students</button>
        </form>
    </div>
    <div class="year">
        <?php echo "<p>Academic Year: $lastyear / $currentyear</p>"; ?>
    </div>
    <div class="container">
        <div class="box" style="background: red;">
            <a href="form1Students.php">
                <span class="icon-student"><i class="fa-solid fa-user-graduate"></i></span>
                FORM ONE
            </a>
        </div>
        <div class="box" style="background: green;">
            <a href="form2Students.php">
                <span class="icon-student"><i class="fa-solid fa-user-graduate"></i></span>
                FORM TWO
            </a>
        </div>
        <div class="box" style="background: rgb(76, 167, 220);">
            <a href="form3Students.php">
                <span class="icon-student"><i class="fa-solid fa-user-graduate"></i></span>
                FORM THREE
            </a>
        </div>
        <div class="box" style="background: yellow;">
            <a href="form4Students.php">
                <span class="icon-student"><i class="fa-solid fa-user-graduate"></i></span>
                FORM FOUR
            </a>
        </div>
    </div>
</body>
</html>
