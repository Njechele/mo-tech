<?php
session_start();
include "./includes/db.php";
include "header2.php";

// Hakikisha user amelogin na ana class_id
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !isset($_SESSION['class_id'])) {
    header("location: index.php");
    exit();
}

$class_id = $_SESSION['class_id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Ikiwa mwalimu ametuma jibu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'], $_POST['comment_id'])) {
    $reply = mysqli_real_escape_string($conn, $_POST['reply']);
    $comment_id = intval($_POST['comment_id']);

    $update_sql = "UPDATE comments SET teacher_reply = '$reply' WHERE comment_id = $comment_id AND class_id = $class_id";
    mysqli_query($conn, $update_sql);
}

// pata comments kwa wanafunzi wa darasa lake
$sql = "SELECT comments.comment_id, users.user_id, users.first_name, users.middle_name, users.last_name, comments.comment, comments.teacher_reply
        FROM users 
        JOIN comments ON users.user_id = comments.user_id 
        WHERE comments.class_id = $class_id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Comments & Reply</title>
  <style>
    body {
      font-family: 'poppins';
      background-color: whitesmoke;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
      padding-top: 90px;
    }
    h2 {
      text-align: center;
      color: #337ab7;
      margin-bottom: 30px;
      font-size: 24px;
      letter-spacing: 1px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.08);
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
      vertical-align: top;
      font-size: 15px;
    }
    th {
      background: #3b4265;
      color: white;
      font-weight: 600;
    }
    tr:nth-child(even) {
      background-color: #f7f7f7;
    }
    .comment-box {
      background: #e6f7ff;
      border-left: 5px solid #337ab7;
      padding: 10px;
      border-radius: 5px;
      font-size: 15px;
    }
    .reply-box {
      margin-top: 10px;
    }
    textarea {
      width: 100%;
      padding: 8px;
      resize: vertical;
      border-radius: 5px;
      border: 1px solid #aaa;
      font-size: 15px;
    }
    .submit-btn {
      margin-top: 5px;
      background-color: #337ab7;
      color: white;
      padding: 6px 18px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 15px;
      transition: background 0.2s;
    }
    .submit-btn:hover {
      background-color: #235a96;
    }
    .teacher-reply {
      margin-top: 5px;
      font-style: italic;
      color: #218838;
      padding-left: 10px;
      background: #e6ffe6;
      border-left: 4px solid #28a745;
      border-radius: 5px;
      padding: 8px 10px;
      font-size: 15px;
    }
    .no-comment {
      text-align: center;
      font-style: italic;
      color: #888;
      font-size: 16px;
      padding: 20px 0;
    }
    @media (max-width: 900px) {
      .container { padding: 10px 2px 15px 2px; }
      table, th, td { font-size: 13px; }
      h2 { font-size: 18px; }
    }
  </style>
</head>
<body>
<div class="container">
  <h2><i class="fa fa-comments"></i> Parent Comments & Teacher Replies</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Student Name</th>
        <th>Comment</th>
        <th>Reply</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $id = $row['user_id'];
              $first = $row['first_name'];
              $middleInitial = strtoupper(substr($row['middle_name'], 0, 1));
              $last = $row['last_name'];
              $full_name = "$first $middleInitial. $last";
              $comment = htmlspecialchars($row['comment']);
              $reply = htmlspecialchars($row['teacher_reply']);
              $comment_id = $row['comment_id'];

              echo "<tr>";
              echo "<td>$id</td>";
              echo "<td>$full_name</td>";
              echo "<td><div class='comment-box'>$comment</div></td>";
              echo "<td>";

              if ($reply) {
                echo "<div class='teacher-reply'><strong>Reply:</strong> $reply</div>";
              } else {
                echo "<form method='POST' class='reply-box'>
                        <textarea name='reply' rows='2' required placeholder='Type your reply...'></textarea>
                        <input type='hidden' name='comment_id' value='$comment_id'>
                        <button class='submit-btn' type='submit'>Send Reply</button>
                      </form>";
              }
              echo "</td></tr>";
          }
      } else {
          echo "<tr><td colspan='4' class='no-comment'>No comments available</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<?php include "footer.php"; ?>

</body>
</html>
