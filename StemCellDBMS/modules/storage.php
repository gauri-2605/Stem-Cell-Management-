<?php
require_once __DIR__ . '/../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $donor_id = intval($_POST['donor_id']);
        $storage_location = mysqli_real_escape_string($conn, $_POST['storage_location'] ?? '');
        $collected_date = mysqli_real_escape_string($conn, $_POST['collected_date'] ?? '');
        $units = intval($_POST['units'] ?? 1);

        // Set expiry date = 10 years after collection
        $sql = "INSERT INTO storage (donor_id, storage_location, collected_date, expiry_date, units)
                VALUES ($donor_id, '$storage_location', '$collected_date', DATE_ADD('$collected_date', INTERVAL 10 YEAR), $units)";
        mysqli_query($conn, $sql);
    }

    if ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        mysqli_query($conn, "DELETE FROM storage WHERE storage_id=$id");
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch donor list
$donors = mysqli_query($conn, "SELECT donor_id, name FROM donors ORDER BY name");

// Search
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $res = mysqli_query($conn, "SELECT s.*, d.name AS donor_name
                                FROM storage s
                                JOIN donors d ON s.donor_id = d.donor_id
                                WHERE d.name LIKE '%$search%' 
                                   OR s.storage_location LIKE '%$search%'
                                ORDER BY s.storage_id DESC");
} else {
    $res = mysqli_query($conn, "SELECT s.*, d.name AS donor_name
                                FROM storage s
                                JOIN donors d ON s.donor_id = d.donor_id
                                ORDER BY s.storage_id DESC");
}
?>

<!doctype html>
<html>
<head>
<link href="../style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
<h2>Storage Management</h2>
<a href="../index.php" class="btn btn-secondary mb-3">Back</a>

<!-- 🔍 Search Form -->
<form method="GET" class="mb-3 d-flex">
  <input type="text" name="search" class="form-control me-2" placeholder="Search by donor or location..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
  <button type="submit" class="btn btn-success me-2">Search</button>
  <a href="storage.php" class="btn btn-secondary">Clear</a>
</form>

<!-- ➕ Add Storage -->
<form method="post" class="mb-4 row g-2">
  <div class="col-md-3">
    <select name="donor_id" class="form-select" required>
      <option value="">Select Donor</option>
      <?php while ($d = mysqli_fetch_assoc($donors)) { ?>
        <option value="<?php echo $d['donor_id']; ?>"><?php echo htmlspecialchars($d['name']); ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="col-md-2"><input name="storage_location" class="form-control" placeholder="Storage Location" required></div>
  <div class="col-md-2"><input name="collected_date" type="date" class="form-control" required></div>
  <div class="col-md-1"><input name="units" type="number" class="form-control" value="1" min="1"></div>
  <input type="hidden" name="action" value="add">
  <div class="col-12"><button class="btn btn-primary">Add Storage</button></div>
</form>

<!-- 📋 Storage Table -->
<table class="table table-striped">
<thead>
<tr>
  <th>ID</th>
  <th>Donor</th>
  <th>Storage Location</th>
  <th>Collected Date</th>
  <th>Expiry Date</th>
  <th>Units</th>
  <th>Actions</th>
</tr>
</thead>
<tbody>
<?php 
if (mysqli_num_rows($res) > 0) {
  while ($row = mysqli_fetch_assoc($res)) { ?>
<tr>
  <td><?php echo $row['storage_id']; ?></td>
  <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
  <td><?php echo htmlspecialchars($row['storage_location']); ?></td>
  <td><?php echo htmlspecialchars($row['collected_date']); ?></td>
  <td><?php echo htmlspecialchars($row['expiry_date']); ?></td>
  <td><?php echo htmlspecialchars($row['units']); ?></td>
  <td>
    <form method="post" style="display:inline">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" value="<?php echo $row['storage_id']; ?>">
      <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
    </form>
  </td>
</tr>
<?php } 
} else { ?>
<tr><td colspan="7" class="text-center text-muted">No records found</td></tr>
<?php } ?>
</tbody>
</table>
</body>
</html>
