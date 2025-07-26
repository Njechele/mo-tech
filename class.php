<?php
session_start();
include 'header.php';
include 'footer.php';
include './includes/db.php';

$message = '';

// Sanitize GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $class_id = intval($_GET['id']);

    $checkUsers = mysqli_query($conn, "SELECT * FROM users WHERE class_id = $class_id");
    $checkResult = mysqli_query($conn, "SELECT * FROM result WHERE class_id = $class_id");

    if(mysqli_num_rows($checkUsers) > 0 || mysqli_num_rows($checkResult) > 0){
        $message = 'Class cannot be deleted because it has related users or results.';
    } else {
        $delete = "DELETE FROM class WHERE class_id = $class_id";
        if(mysqli_query($conn, $delete)){
            echo "<script>alert('Class deleted successfully'); window.location.href='class.php';</script>";
            exit();
        }
    }
}

// Handle Edit
if (isset($_POST['save_edit'])) {
    $edit_class_id = intval($_POST['edit_class_id']);
    $edit_class_name = mysqli_real_escape_string($conn, $_POST['edit_class_name']);

    $update_sql = "UPDATE class SET class_name = '$edit_class_name' WHERE class_id = $edit_class_id";
    if ($conn->query($update_sql)) {
        echo "<script>alert('Class updated successfully'); window.location.href='class.php';</script>";
        exit();
    } else {
        echo "<script>alert('Update failed');</script>";
    }
}

$sql = "SELECT * FROM class";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>student report management system</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'poppins';
            background-color: whitesmoke;
        }
      
        .result-container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        .add-btn {
            display: inline-block;
            background: blue;
            color: white;
            padding: 10px 20px;
            margin-top: 40px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-decoration: none;
        }
        .message {
            color: red;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #3b4265;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .delete {
            background: red;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
        }
        .edit {
            background: green;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px;
        }

        .no-class {
            color: red;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }

        /* Modal styles */
        #editModal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .modal-content {
            background: white;
            width: 90%;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 10px;
            position: relative;
        }
        .modal-content input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 2px solid #ccc;
            border-radius: 6px;
        }
        .save {
            background: green;
            padding: 10px 15px;
            color: white;
            border: none;
            margin-top: 10px;
            border-radius: 6px;
            cursor: pointer;
        }
        .cancel {
            background: red;
            float: right;
            padding: 10px 15px;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }

        @media(max-width: 600px) {
            .add-btn, .edit, .delete {
                font-size: 14px;
                padding: 5px 10px;
            }
        }
    </style>
</head>
<body>

    <div class="result-container">
        <a href="addClass.php" class="add-btn">+ Add Class</a>
        
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Class Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['class_id'] ?></td>
                    <td><?= htmlspecialchars($row['class_name']) ?></td>
                    <td>
                        <a href="#" class="edit" onclick="openEditModal('<?= $row['class_id'] ?>', '<?= htmlspecialchars($row['class_name']) ?>')">Edit</a>
                        <a href="?id=<?= $row['class_id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this class?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="no-class">No class available</div>
        <?php endif; ?>
    </div>

    <!-- Edit Modal -->
    <div id="editModal">
        <div class="modal-content">
            <h3>Edit Class</h3>
            <form method="POST" action="">
                <input type="hidden" name="edit_class_id" id="edit_class_id">
                <label for="edit_class_name">Class Name:</label>
                <input type="text" name="edit_class_name" id="edit_class_name" required>
                <br>
                <button type="submit" name="save_edit" class="save">Save</button>
                <button type="button" onclick="closeModal()" class="cancel">Cancel</button>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        function openEditModal(classId, className) {
            document.getElementById('edit_class_id').value = classId;
            document.getElementById('edit_class_name').value = className;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>
