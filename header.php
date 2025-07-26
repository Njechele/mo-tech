<?php
include './includes/db.php';

// Pata jina la page iliyopo sasa
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/fontawesome.min.css" rel="stylesheet">
    <style>
       *{
            margin: 0;
            padding: 0;
            border: none;
            outline: none;
        }
        body{
            font-family: 'poppins';
            margin: 0;
            padding: 0;
            text-decoration: none;
        }
        .header{
            z-index: 1000;
            overflow: hidden;
        }
        .header p{
            position: fixed;
            padding: 15px;
            top: 0;
            left: 0;
            width: 100%;
            background: #fff;
            box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .header h2{
            text-align: center;
            margin-top: 50px;
            background: #fff;
            box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
            width: 100%;
            top: 0;
            position: fixed;
        }
        .nav-link{
            display: flex;
            flex-direction: column;
            position: fixed;
            margin-top: 40px;
            top: 0;
            background: rgba(61, 46, 46, 0.801);
            width: 15%; 
            height: 100vh;  
        }
        .nav-link a{
            font-size: 17px;
            padding: 14px 20px;
            margin: 10px 5px; 
            text-decoration: none;
            color: white;
            transition: background 0.4s ease-in-out;
            position: relative;
        }
        .nav-link a.active, .dropdown li a.active {
            background: rgb(59, 66, 101);
            color: #222 !important;
        }
        .nav-link a:hover{
            background: rgb(59, 66, 101);
        }
        .nav-link a i{
            margin-right: 10px;
            font-size: 20px;
        }
        .dropdown{
            display: none;
            list-style-type: none;
            padding: 0;
            margin: 0 0 0 20px;
            background: rgba(61, 46, 46, 0.95);
            border-radius: 0 0 8px 8px;
        }  
        .dropdown li a.users{
            margin-left: 0;
            color: #fff;
            padding: 8px 20px;
            display: block;
            font-size: 16px;
        }
        .dropdown li a.users:hover{
            background: #3b4265;
            color: #ffd700;
        }
        .nav-link a.has-dropdown::after {
            content: '\f078';
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-left: 8px;
            font-size: 12px;
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
    <script>
        function Dropdown(id){
            var Dropdown = document.getElementById(id);
            Dropdown.style.display = (Dropdown.style.display === "none" || Dropdown.style.display ==="")? "block": "none"; 
        }
        // Optional: Close other dropdowns when one is opened
        document.addEventListener('click', function(e) {
            if (!e.target.matches('.has-dropdown')) {
                document.querySelectorAll('.dropdown').forEach(function(drop) {
                    drop.style.display = "none";
                });
            }
        });
    </script>
</head>
<body>
    <div class="header">
        <p>STUDENT REPORT MANAGEMENT SYSTEM</p>
        <h2>ADMIN</h2>
    </div>
    <div class="nav-link">
        <a href="adminPage.php" class="<?php echo ($current_page == 'adminPage.php') ? 'active' : ''; ?>"><i class="fa fa-home"></i> Dashboard</a>
        <a href="changePassword.php" class="<?php echo ($current_page == 'changePassword.php') ? 'active' : ''; ?>"><i class="fa fa-key"></i> Change Password</a>
        <a href="viewTerm.php" class="<?php echo ($current_page == 'viewTerm.php') ? 'active' : ''; ?>"><i class="fa fa-calendar-alt"></i> View Term</a>
        <a href="class.php" class="<?php echo ($current_page == 'class.php') ? 'active' : ''; ?>"><i class="fa fa-layer-group"></i> Class Level</a>
        
        <!-- Student History Dropdown -->
        <a href="#" class="has-dropdown <?php echo in_array($current_page, ['graduated_student.php','student_history.php']) ? 'active' : ''; ?>" onclick="event.stopPropagation(); Dropdown('historyDrop')">
            <i class="fa fa-graduation-cap"></i> Student History
        </a>
        <ul id="historyDrop" class="dropdown">
            <li><a href="graduated_student.php" class="users <?php echo ($current_page == 'graduated_student.php') ? 'active' : ''; ?>">Graduated Students</a></li>
            <li><a href="student_history.php" class="users <?php echo ($current_page == 'student_history.php') ? 'active' : ''; ?>">Continuous History</a></li>
        </ul>

        <!-- Add Users Dropdown -->
        <a href="#" class="has-dropdown <?php echo in_array($current_page, ['AddTeacher.php','AddStudent.php']) ? 'active' : ''; ?>" onclick="event.stopPropagation(); Dropdown('addUserDrop')">
            <i class="fa fa-user-plus"></i> Add Users
        </a>
        <ul id="addUserDrop" class="dropdown">
            <li><a href="AddTeacher.php" class="users <?php echo ($current_page == 'AddTeacher.php') ? 'active' : ''; ?>">Add Teacher</a></li>
            <li><a href="AddStudent.php" class="users <?php echo ($current_page == 'AddStudent.php') ? 'active' : ''; ?>">Add Student</a></li>
        </ul>

        <a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/js/all.min.js" crossorigin="anonymous"></script>
</body>
</html>