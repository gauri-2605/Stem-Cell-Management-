<?php
require_once __DIR__ . '/../config/db_connect.php';

// ➕ Add & Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $age = intval($_POST['age']);
        $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $disease = mysqli_real_escape_string($conn, $_POST['disease']);

        mysqli_query($conn, "INSERT INTO patients (name, age, blood_group, contact, disease)
                             VALUES ('$name', $age, '$blood_group', '$contact', '$disease')");
    }

    if ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        mysqli_query($conn, "DELETE FROM patients WHERE patient_id = $id");
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// ➤ Search using stored procedure (GET)
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    // Try stored procedure
    $res = @mysqli_query($conn, "CALL search_patient('$search')");
    if ($res === false) {
        // fallback to direct query if procedure fails
        $res = mysqli_query($conn, "SELECT * FROM patients
                                   WHERE name LIKE '%$search%'
                                      OR blood_group LIKE '%$search%'
                                      OR contact LIKE '%$search%'
                                      OR disease LIKE '%$search%'
                                   ORDER BY patient_id DESC");
    } else {
        mysqli_next_result($conn);
    }
} else {
    $res = mysqli_query($conn, "SELECT * FROM patients ORDER BY patient_id DESC");
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Patients</title>
  <link href="../style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>Patients</h2>
  <a href="../index.php" class="btn btn-secondary mb-3">Back</a>

  <!-- Search -->
  <form method="get" class="mb-3 d-flex">
    <input type="text" name="search" class="form-control me-2" placeholder="Search patient..."
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit" class="btn btn-success me-2">Search</button>
    <a href="patients.php" class="btn btn-secondary">Clear</a>
  </form>

  <!-- Add Patient Form -->
  <form method="post" class="mb-4 row g-2">
    <div class="col-md-4">
      <input name="name" class="form-control" placeholder="Name" required>
    </div>
    <div class="col-md-1">
      <input name="age" type="number" class="form-control" placeholder="Age">
    </div>
    <div class="col-md-2">
      <input name="blood_group" class="form-control" placeholder="Blood Group">
    </div>
    <div class="col-md-3">
      <input name="contact" class="form-control" placeholder="Contact">
    </div>
    <div class="col-md-2">
      <input name="disease" class="form-control" placeholder="Disease">
    </div>

    <input type="hidden" name="action" value="add">
    <div class="col-12">
      <button class="btn btn-primary">Add Patient</button>
    </div>
  </form>

  <!-- Table -->
  <table class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>BG</th>
        <th>Contact</th>
        <th>Disease</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($res && mysqli_num_rows($res) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
          <tr>
            <td><?= $row['patient_id']; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['age']); ?></td>
            <td><?= htmlspecialchars($row['blood_group']); ?></td>
            <td><?= htmlspecialchars($row['contact']); ?></td>
            <td><?= htmlspecialchars($row['disease']); ?></td>
            <td>
              <form method="post" style="display:inline">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $row['patient_id']; ?>">
                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center text-muted">No records found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
