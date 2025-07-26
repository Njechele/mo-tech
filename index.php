<?php
session_start();
include './includes/db.php';

$loginErrorMessage = '';
$logout_massage = '';
if(isset($_SESSION['logout_message'])){
    $logout_massage = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the user exists with matching username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $results = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($results);

    // If user is found and password matches hash
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['class_id'] = $row['class_id'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];

        // Redirect based on the user's role
        switch ($row['role']) {
            case 'admin':
                header("Location: adminPage.php");
                break;
            case 'student':
                header("Location: parentPage.php");
                break;
            case 'form1_teacher':
            case 'form2_teacher':
            case 'form3_teacher':
                header("Location: teachers.php");
                break;
            default:
                header("Location: teachers.php");
                break;
        }
        exit();
    } else {
        $loginErrorMessage = 'Invalid username or password!';
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        *{
            margin: 0;
            padding: 0;
            border: none;
            outline: none;
            box-sizing: border-box;
        }
        body{
            font-family: 'poppins';
            background-color: whitesmoke;
        }
        .intro-box {
            background: #f7f7f7;
            border-left: 5px solid #3b4265;
            border-radius: 8px;
            margin: 40px auto 25px auto;
            padding: 22px 28px;
            font-size: 16px;
            color: #333;
            max-width: 500px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .intro-box strong {
            color: #3b4265;
            font-size: 20px;
            display: block;
            margin-bottom: 10px;
        }
        .intro-box ul {
            margin: 12px 0 0 18px;
            color: #444;
            font-size: 15px;
        }
        .intro-box li {
            margin-bottom: 6px;
        }
        .intro-box .note {
            margin-top: 14px;
            color: #3b4265;
            font-size: 15px;
            font-style: italic;
        }
        .login-container{
            background: #FFF;
            box-shadow: 10px 10px 15px rgba(0,0,0,0.1);
            width: 500px;
            margin: 0 auto 40px auto;
            border-radius: 10px;
        }
        .login-container p{
            background: #ffe066;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            margin-bottom: 15px;
            border-radius: 10px 10px 0 0;
            color: #3b4265;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .form {
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        .input-group {
            position: relative;
            width: 100%;
            margin-bottom: 18px;
        }
        .input-group input{
            padding: 15px 40px 15px 40px;
            border-radius: 5px;
            width: 100%;
            border: 2px solid transparent;
            background-color:  rgba(0, 0, 0, 0.1);
            font-size: 16px;
            transition: border 0.2s;
        }
        .input-group input:focus{
            border: 2px solid blue;
        }
        .input-group .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #3b4265;
            font-size: 18px;
        }
        .input-group .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #3b4265;
            font-size: 18px;
            cursor: pointer;
            background: none;
            border: none;
            outline: none;
        }
        .login{
            background: blue;
            color: white;
            border: none;
            padding: 10px 50px;
            border-radius: 10px;
            transition: 0.3s ease-in-out;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
        }
        .login:hover{
            background: #23284a;
            letter-spacing: 1.3px;
        }
        .error{
            background: rgba(202, 71, 71, 0.77);
            border: none;
            text-align: center;
            margin: 5px auto 10px auto;
            padding: 8px;
            color: white;
            border-radius: 5px;
            width: 90%;
            animation: stayThenFade 1s ease-out 3s forwards;
        }
        .logout-alert {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            text-align: center;
            margin: 5px auto 10px auto;
            padding: 8px;
            border-radius: 5px;
            width: 90%;
            font-size: 15px;
        }
        @keyframes stayThenFade{
            to { opacity: 0;}
        }
        @media (max-width: 600px) {
            .login-container, .intro-box {
                width: 98%;
                max-width: 98%;
                padding: 10px;
            }
            .intro-box {
                padding: 12px 8px;
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
    <div class="intro-box">
        <strong>Welcome to the Student Report Management System</strong>
        <div>
            This system helps schools to manage and share student academic results easily and securely.
        </div>
        <ul>
            <li><b>Teachers</b> can upload and manage students' results for each term.</li>
            <li><b>Parents/Students</b> can view academic performance and send feedback or comments.</li>
            <li><b>Admins</b> can manage users, terms, and oversee all activities.</li>
        </ul>
        <div class="note">
            Please login below using your username and password to access your dashboard.
        </div>
    </div>
    <div class="login-container">
        <p>LOGIN</p>
        <?php if (!empty($loginErrorMessage)): ?>
            <div class="error"><?php echo $loginErrorMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($logout_massage)): ?>
            <div class="logout-alert"><?php echo $logout_massage; ?></div>
        <?php endif; ?>
        <form class="form" action="" method="POST" autocomplete="off">
            <div class="input-group">
                <span class="input-icon"><i class="fa fa-user"></i></span>
                <input type="text" placeholder="Username" name="username" required>
            </div>
            <div class="input-group">
                <span class="input-icon"><i class="fa fa-lock"></i></span>
                <input type="password" placeholder="Password" name="password" id="password" required>
                <button type="button" class="toggle-password" onclick="togglePassword()">
                    <i class="fa fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            <button type="submit" class="login">Log In</button>
        </form>
    </div>
    <script>
        function togglePassword() {
            var pwd = document.getElementById('password');
            var icon = document.getElementById('eyeIcon');
            if (pwd.type === "password") {
                pwd.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    <?php include "footer.php"; ?>
</body>
</html>