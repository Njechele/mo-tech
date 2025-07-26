<?php
session_start();
include "./includes/db.php";
include "parent_header.php";

//  mzazi au mwanafunzi amelogin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Chukua comments za mwanafunzi huyu
$sql = "SELECT comment_id, comment, teacher_reply FROM comments WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Parent Comment View</title>
  <style>
    body {
      font-family: 'poppins';
      background-color: whitesmoke;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 700px;
      margin: 60px auto 30px auto;
      background: #fff;
      padding: 30px 25px 25px 25px;
      box-shadow: 0 4px 18px rgba(0,0,0,0.08);
      border-radius: 12px;
    }
    .your-comment {
      text-align: center;
      color: #337ab7;
      margin-bottom: 30px;
      letter-spacing: 1px;
      font-size: 22px;
    }
    .comment-block {
      margin-bottom: 22px;
      border-left: 5px solid #337ab7;
      background: #f8fbff;
      padding: 13px 18px 10px 18px;
      border-radius: 7px;
      box-shadow: 0 1px 5px rgba(51,123,183,0.04);
      position: relative;
      font-size: 15px;
    }
    .comment-block strong {
      color: #337ab7;
      font-size: 15px;
    }
    .reply-block {
      margin-top: 10px;
      background: #e6ffe6;
      border-left: 4px solid #28a745;
      padding: 10px 15px;
      border-radius: 5px;
      color: #218838;
      font-size: 15px;
    }
    .no-reply {
      background: #fff3cd;
      border-left: 4px solid #ffc107;
      color: #856404;
      margin-top: 10px;
      padding: 10px 15px;
      border-radius: 5px;
      font-size: 15px;
    }
    .no-comment {
      text-align: center;
      font-style: italic;
      padding: 30px 0 10px 0;
      color: #888;
      font-size: 16px;
    }
    @media (max-width: 700px) {
      .container { padding: 10px 2px 15px 2px; }
      h2 { font-size: 18px; }
      .comment-block { padding: 10px 7px 7px 10px; }
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

<div class="container">
  <h2 class="your-comment"><i class="fa fa-comments"></i> Your Submitted Comments</h2>

  <?php
  if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
          $comment = htmlspecialchars($row['comment']);
          $reply = htmlspecialchars($row['teacher_reply']);

          echo "<div class='comment-block'>";
          echo "<strong>Your Comment:</strong><br>$comment";

          if (!empty($reply)) {
              echo "<div class='reply-block'><strong>Teacher's Reply:</strong><br>$reply</div>";
          } else {
              echo "<div class='no-reply'>No reply yet from teacher.</div>";
          }

          echo "</div>";
      }
  } else {
      echo "<div class='no-comment'><i class='fa fa-info-circle'></i> You have not submitted any comments yet.</div>";
  }
  ?>
</div>

<?php include "footer.php"; ?>
</body>
</html>
