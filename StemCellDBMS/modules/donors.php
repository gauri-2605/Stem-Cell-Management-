<?php
require_once __DIR__ . '/../config/db_connect.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  if($_POST['action'] === 'add') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = intval($_POST['age']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $donation_date = mysqli_real_escape_string($conn, $_POST['donation_date']);
    mysqli_query($conn, "INSERT INTO donors (name, age, blood_group, contact, donation_date) VALUES ('$name',$age,'$blood_group','$contact','$donation_date')");
  }
  if($_POST['action'] === 'update' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = intval($_POST['age']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $donation_date = mysqli_real_escape_string($conn, $_POST['donation_date']);
    mysqli_query($conn, "UPDATE donors SET name='$name', age=$age, blood_group='$blood_group', contact='$contact', donation_date='$donation_date' WHERE donor_id=$id");
  }
  if($_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM donors WHERE donor_id=$id");
  }
  header('Location: ' . $_SERVER['PHP_SELF']);
  exit;
}

$mode = 'add';
$edit_row = null;
if(isset($_GET['edit'])){
  $mode = 'edit';
  $eid = intval($_GET['edit']);
  $r = mysqli_query($conn, "SELECT * FROM donors WHERE donor_id=$eid LIMIT 1");
  if($r) $edit_row = mysqli_fetch_assoc($r);
}

// -------- SEARCH FUNCTION USING STORED PROCEDURE ----------
if (isset($_GET['search']) && $_GET['search'] !== '') {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $sql = "CALL search_donor('$search')";
  $res = mysqli_query($conn, $sql);
  mysqli_next_result($conn); // clear buffer
} else {
  $res = mysqli_query($conn, "SELECT * FROM donors ORDER BY created_at DESC");
}
?>
<!doctype html>
<html><head>
<link href="../style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="container py-4">
<h2>Donors</h2>
<a href="../index.php" class="btn btn-secondary mb-3">Back</a>

<!-- 🔍 Search Form -->
<form method="GET" class="mb-3 d-flex">
  <input type="text" name="search" class="form-control me-2" placeholder="Search donor by name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
  <button type="submit" class="btn btn-success me-2">Search</button>
  <a href="donor.php" class="btn btn-secondary">Clear</a>
</form>

<form method="post" class="mb-4 row g-2">
  <div class="col-md-4"><input name="name" class="form-control" placeholder="Name" required value="<?php echo $edit_row ? htmlspecialchars($edit_row['name']) : ''; ?>"></div>
  <div class="col-md-1"><input name="age" type="number" class="form-control" placeholder="Age" value="<?php echo $edit_row ? intval($edit_row['age']) : ''; ?>"></div>
  <div class="col-md-2"><input name="blood_group" class="form-control" placeholder="Blood Group" value="<?php echo $edit_row ? htmlspecialchars($edit_row['blood_group']) : ''; ?>"></div>
  <div class="col-md-3"><input name="contact" class="form-control" placeholder="Contact" value="<?php echo $edit_row ? htmlspecialchars($edit_row['contact']) : ''; ?>"></div>
  <div class="col-md-2"><input name="donation_date" type="date" class="form-control" placeholder="Donation Date" value="<?php echo $edit_row ? htmlspecialchars($edit_row['donation_date']) : ''; ?>"></div>
  <input type="hidden" name="action" value="<?php echo $mode === 'edit' ? 'update' : 'add'; ?>">
  <?php if($mode === 'edit'){ ?>
    <input type="hidden" name="id" value="<?php echo intval($edit_row['donor_id']); ?>">
  <?php } ?>
  <div class="col-12"><button class="btn btn-primary"><?php echo $mode==='edit' ? 'Update Donor' : 'Add Donor'; ?></button></div>
</form>

<table class="table table-striped">
<thead><tr><th>ID</th><th>Name</th><th>Age</th><th>BG</th><th>Contact</th><th>Donation Date</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($res)){ ?>
<tr>
  <td><?php echo $row['donor_id']; ?></td>
  <td><?php echo htmlspecialchars($row['name']); ?></td>
  <td><?php echo $row['age']; ?></td>
  <td><?php echo $row['blood_group']; ?></td>
  <td><?php echo $row['contact']; ?></td>
  <td><?php echo $row['donation_date']; ?></td>
  <td>
    <a href="?edit=<?php echo $row['donor_id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
    <form method="post" style="display:inline">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" value="<?php echo $row['donor_id']; ?>">
      <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
    </form>
  </td>
</tr>
<?php } ?>
</tbody>
</table>
</body></html>
