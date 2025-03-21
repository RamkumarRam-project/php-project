<?php
// Connect to MySQL Database
$connection = mysqli_connect("localhost", "root", "", "crud");

// Check connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Insert or Update Data when form is submitted
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];

    if ($id == "") {
        // Insert New Record
        $sql = "INSERT INTO student (name, address, mobile) VALUES ('$name', '$address', '$mobile')";
    } else {
        // Update Existing Record
        $sql = "UPDATE student SET name='$name', address='$address', mobile='$mobile' WHERE id=$id";
    }

    if (mysqli_query($connection, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

// Delete Student Record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM student WHERE id=$id");
    header("Location: index.php");
    exit();
}

// Fetch All Records
$result = mysqli_query($connection, "SELECT * FROM student");

// Edit Student Record
$edit_name = "";
$edit_address = "";
$edit_mobile = "";
$edit_id = "";

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_result = mysqli_query($connection, "SELECT * FROM student WHERE id=$id");
    $row = mysqli_fetch_assoc($edit_result);
    $edit_id = $row['id'];
    $edit_name = $row['name'];
    $edit_address = $row['address'];
    $edit_mobile = $row['mobile'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e3f2fd; } /* Light blue background */
        .container { margin-top: 50px; }
        .card { animation: fadeIn 0.5s; border-radius: 12px; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .btn { transition: 0.3s; }
        .btn:hover { transform: scale(1.05); }
        table { background: white; border-radius: 12px; overflow: hidden; }
        th, td { vertical-align: middle !important; }
        @media (max-width: 500px) {
        .edit {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 4px;
            padding: 1px;
            color: white;
        }.remove{
            padding: 3px;
        }
        .edit a {
            margin-bottom: 5px;
            width: 100%;
            text-align: center;
        }
    }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-6 ">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3><?= $edit_id ? "Edit Student" : "Register Student" ?></h3>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?= $edit_id ?>">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="<?= $edit_name ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="<?= $edit_address ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" class="form-control" value="<?= $edit_mobile ?>" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-<?= $edit_id ? 'warning' : 'success' ?> w-100">
                            <?= $edit_id ? "Update Student" : "Register Student" ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

         <div class="col-md-6 ">

            <div class="card mt-4 shadow-lg">
                <div class="card-header bg-dark text-white text-center">
                    <h3>Student List</h3>
                </div>
                <div class="card-body">
    <div class="table-responsive" style="max-width: 600px; overflow-x: auto;">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['address'] ?></td>
                        <td><?= $row['mobile'] ?></td>
                        <td>
                            <a href="index.php?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm edit">Edit</a>
                            <a href="index.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm remove"  onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
