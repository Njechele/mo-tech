<?php
session_start();
include './includes/db.php';
include 'parent_header.php';

$user_id = $_SESSION['user_id'] ?? 0;
$message = '';

if (!$user_id) {
    header("Location: logout.php");
    exit();
}

// Update email
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['parent_email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['parent_email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<span style="color:red;">Invalid email format!</span>';
    } else {
        mysqli_query($conn, "UPDATE users SET parent_email='$email' WHERE user_id=$user_id");
        $message = '<span style="color:green;">Email updated successfully!</span>';
    }
}

// Change password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    if ($new_password === $confirm_password && strlen($new_password) >= 6) {
        mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE user_id=$user_id");
        $message = '<span style="color:green;">Password changed successfully!</span>';
    } else {
        $message = '<span style="color:red;">Passwords do not match or are too short (min 6 chars).</span>';
    }
}

// Fetch current email and name
$res = mysqli_query($conn, "SELECT parent_email, first_name, last_name FROM users WHERE user_id=$user_id");
$row = mysqli_fetch_assoc($res);
$current_email = $row['parent_email'] ?? '';
$full_name = $row['first_name'] . ' ' . $row['last_name'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parent Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body { font-family: 'poppins'; background: whitesmoke; }
        .profile-container {
            background: #fff; margin: 60px auto 0 auto; max-width: 400px;
            border-radius: 10px; box-shadow: 0 2px 10px rgba(59,66,101,0.08);
            padding: 30px 30px 20px 30px;
        }
        .profile-container h2 { text-align: center; color: #3b4265; }
        label { font-weight: 600; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #aaa; margin-bottom: 15px;
        }
        button {
            background: #3498db; color: #fff; border: none; padding: 8px 24px;
            border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer;
            transition: background 0.2s;
        }
        button:hover { background: #217dbb; }
        .section-title { margin-top: 25px; margin-bottom: 10px; color: #217dbb; }
        hr { margin: 25px 0 15px 0; }
        .msg { text-align:center; margin-top:10px; }
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
<div class="profile-container">
    <h2><i class="fa fa-user"></i> Parent Profile</h2>
    <?php if ($message): ?>
        <div class="msg"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="section-title"><i class="fa fa-envelope"></i> Update Email</div>
        <label>Full Name:</label>
        <div style="margin-bottom:10px;"><?php echo htmlspecialchars($full_name); ?></div>
        <label for="email">Email Address:</label>
        <input type="email" name="parent_email" id="email" value="<?php echo htmlspecialchars($current_email); ?>" required>
        <button type="submit">Update Email</button>
    </form>
    <hr>
    <form method="post">
        <div class="section-title"><i class="fa fa-key"></i> Change Password</div>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" minlength="6" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" minlength="6" required>
        <button type="submit">Change Password</button>
    </form>
</div>
</body>
</html>