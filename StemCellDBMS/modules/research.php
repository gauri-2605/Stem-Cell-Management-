<?php
require_once __DIR__ . '/../config/db_connect.php';

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

  if ($_POST['action'] === 'add') {
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $lead_scientist = mysqli_real_escape_string($conn, $_POST['lead_scientist']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);

    mysqli_query($conn, "INSERT INTO research (project_name, lead_scientist, status, summary)
                         VALUES ('$project_name', '$lead_scientist', '$status', '$summary')");
  }

  if ($_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM research WHERE research_id = $id");
  }

  header('Location: ' . $_SERVER['PHP_SELF']);
  exit;
}

// ✅ Search using stored procedure
$research_data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_action'])) {
  $search = mysqli_real_escape_string($conn, $_POST['search']);
  $sql = "CALL search_research('$search')";
  
  if (mysqli_multi_query($conn, $sql)) {
    do {
      if ($result = mysqli_store_result($conn)) {
        while ($row = mysqli_fetch_assoc($result)) {
          $research_data[] = $row;
        }
        mysqli_free_result($result);
      }
    } while (mysqli_next_result($conn));
  }
} else {
  // Normal display without search
  $result = mysqli_query($conn, "SELECT * FROM research ORDER BY created_at DESC");
  while ($row = mysqli_fetch_assoc($result)) {
    $research_data[] = $row;
  }
}

mysqli_close($conn);
?>

<!doctype html>
<html>
<head>
  <link href="../style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
<h2>Research Data</h2>
<a href="../index.php" class="btn btn-secondary mb-3">Back</a>

<!-- Add Form -->
<form method="post" class="mb-4 row g-2">
  <div class="col-md-3"><input name="project_name" class="form-control" placeholder="Project Name" required></div>
  <div class="col-md-3"><input name="lead_scientist" class="form-control" placeholder="Lead Scientist"></div>
  <div class="col-md-2"><input name="status" class="form-control" placeholder="Status"></div>
  <div class="col-md-4"><input name="summary" class="form-control" placeholder="Summary"></div>
  <input type="hidden" name="action" value="add">
  <div class="col-12"><button class="btn btn-primary">Add Research</button></div>
</form>

<!-- Search Form -->
<form method="post" class="mb-3 d-flex">
  <input name="search" class="form-control me-2" placeholder="Search by Project Name">
  <input type="hidden" name="search_action" value="1">
  <button class="btn btn-success">Search</button>
</form>

<!-- Table -->
<table class="table table-striped">
<thead>
<tr>
  <th>ID</th>
  <th>Project Name</th>
  <th>Lead Scientist</th>
  <th>Status</th>
  <th>Summary</th>
  <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($research_data as $row): ?>
<tr>
  <td><?php echo $row['research_id']; ?></td>
  <td><?php echo htmlspecialchars($row['project_name']); ?></td>
  <td><?php echo htmlspecialchars($row['lead_scientist']); ?></td>
  <td><?php echo htmlspecialchars($row['status']); ?></td>
  <td><?php echo htmlspecialchars($row['summary']); ?></td>
  <td>
    <form method="post" style="display:inline">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" value="<?php echo $row['research_id']; ?>">
      <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</body>
</html>
