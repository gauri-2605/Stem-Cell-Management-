<?php
require_once __DIR__ . '/../config/db_connect.php';

// 🧩 Handle Add / Delete
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if($_POST['action'] === 'add') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        mysqli_query($conn, "INSERT INTO staff (name, role, department, contact) VALUES ('$name','$role','$department','$contact')");
    }
    if($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        mysqli_query($conn, "DELETE FROM staff WHERE staff_id=$id");
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// 🧠 SEARCH USING STORED PROCEDURE
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "CALL search_staff('$search')";
    $res = mysqli_query($conn, $sql);
    mysqli_next_result($conn); // Clear buffer for next queries
} else {
    $res = mysqli_query($conn, "SELECT * FROM staff ORDER BY created_at DESC");
}
?>

<!doctype html>
<html>
<head>
    <link href="../style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

<h2>Staff</h2>
<a href="../index.php" class="btn btn-secondary mb-3">Back</a>

<!-- 🔍 Search Form -->
<form method="GET" class="mb-3 d-flex">
    <input type="text" name="search" class="form-control me-2" placeholder="Search staff by name..." 
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit" class="btn btn-success me-2">Search</button>
    <a href="staff.php" class="btn btn-secondary">Clear</a>
</form>

<!-- ➕ Add Form -->
<form method="post" class="mb-4 row g-2">
    <div class="col-md-4"><input name="name" class="form-control" placeholder="Name" required></div>
    <div class="col-md-3"><input name="role" class="form-control" placeholder="Role"></div>
    <div class="col-md-3"><input name="department" class="form-control" placeholder="Department"></div>
    <div class="col-md-2"><input name="contact" class="form-control" placeholder="Contact"></div>
    <input type="hidden" name="action" value="add">
    <div class="col-12"><button class="btn btn-primary">Add Staff</button></div>
</form>

<!-- 📋 Table -->
<table class="table table-striped">
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Role</th>
    <th>Department</th>
    <th>Contact</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($res)){ ?>
<tr>
    <td><?php echo $row['staff_id']; ?></td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['role']); ?></td>
    <td><?php echo htmlspecialchars($row['department']); ?></td>
    <td><?php echo htmlspecialchars($row['contact']); ?></td>
    <td>
        <form method="post" style="display:inline">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo $row['staff_id']; ?>">
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>
</tbody>
</table>

</body>
</html>
