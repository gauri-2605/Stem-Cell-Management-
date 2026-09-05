<?php
require_once __DIR__ . '/../config/db_connect.php';

// 🧩 Handle Add / Update / Delete
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  if($_POST['action'] === 'add') {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    mysqli_query($conn, "INSERT INTO inventory (item_name, quantity, unit) VALUES ('$item_name',$quantity,'$unit')");
  }

  if($_POST['action'] === 'update' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    mysqli_query($conn, "UPDATE inventory SET item_name='$item_name', quantity=$quantity, unit='$unit' WHERE item_id=$id");
  }

  if($_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM inventory WHERE item_id=$id");
  }

  header('Location: ' . $_SERVER['PHP_SELF']);
  exit;
}

// 🧠 SEARCH USING STORED PROCEDURE
if (isset($_GET['search']) && $_GET['search'] !== '') {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $sql = "CALL search_inventory('$search')";
  $res = mysqli_query($conn, $sql);
  mysqli_next_result($conn); // clear buffer
} else {
  $res = mysqli_query($conn, "SELECT * FROM inventory ORDER BY last_updated DESC");
}

// 🛠 Edit mode
$mode = 'add';
$edit_row = null;
if(isset($_GET['edit'])){
  $mode = 'edit';
  $eid = intval($_GET['edit']);
  $r = mysqli_query($conn, "SELECT * FROM inventory WHERE item_id=$eid LIMIT 1");
  if($r) $edit_row = mysqli_fetch_assoc($r);
}
?>

<!doctype html>
<html>
<head>
<link href="../style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

<h2>Inventory</h2>
<a href="../index.php" class="btn btn-secondary mb-3">Back</a>

<!-- 🔍 Search Form -->
<form method="GET" class="mb-3 d-flex">
  <input type="text" name="search" class="form-control me-2" placeholder="Search item by name..." 
         value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
  <button type="submit" class="btn btn-success me-2">Search</button>
  <a href="inventory.php" class="btn btn-secondary">Clear</a>
</form>

<!-- ➕ Add / Update Form -->
<form method="post" class="mb-4 row g-2">
  <div class="col-md-5"><input name="item_name" class="form-control" placeholder="Item Name" required 
    value="<?php echo $edit_row ? htmlspecialchars($edit_row['item_name']) : ''; ?>"></div>
  <div class="col-md-3"><input name="quantity" type="number" class="form-control" placeholder="Quantity" required 
    value="<?php echo $edit_row ? intval($edit_row['quantity']) : ''; ?>"></div>
  <div class="col-md-2"><input name="unit" class="form-control" placeholder="Unit (pcs/litres)" 
    value="<?php echo $edit_row ? htmlspecialchars($edit_row['unit']) : ''; ?>"></div>
  <input type="hidden" name="action" value="<?php echo $mode === 'edit' ? 'update' : 'add'; ?>">
  <?php if($mode === 'edit'){ ?>
    <input type="hidden" name="id" value="<?php echo intval($edit_row['item_id']); ?>">
  <?php } ?>
  <div class="col-12"><button class="btn btn-primary"><?php echo $mode==='edit' ? 'Update Item' : 'Add Item'; ?></button></div>
</form>

<!-- 📋 Table -->
<table class="table table-striped">
<thead><tr><th>ID</th><th>Item</th><th>Qty</th><th>Unit</th><th>Last Updated</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($res)){ ?>
<tr>
  <td><?php echo $row['item_id']; ?></td>
  <td><?php echo htmlspecialchars($row['item_name']); ?></td>
  <td><?php echo $row['quantity']; ?></td>
  <td><?php echo $row['unit']; ?></td>
  <td><?php echo $row['last_updated']; ?></td>
  <td>
    <a href="?edit=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
    <form method="post" style="display:inline">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" value="<?php echo $row['item_id']; ?>">
      <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
    </form>
  </td>
</tr>
<?php } ?>
</tbody>
</table>

</body>
</html>
