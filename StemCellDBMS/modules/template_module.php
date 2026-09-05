<?php
// TEMPLATE: copy and adapt for specific modules
require_once __DIR__ . '/../config/db_connect.php';
$table = 'REPLACE_TABLE';
$fields = ['REPLACE_FIELDS']; // for display
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // simple add handler - assumes columns match keys in $_POST
    if($_POST['action'] === 'add') {
        $cols = [];
        $vals = [];
        foreach($_POST as $k=>$v){
            if(in_array($k, explode(',', 'REPLACE_POST_KEYS'))){
                $cols[] = mysqli_real_escape_string($conn, $k);
                $vals[] = "'".mysqli_real_escape_string($conn, $v)."'";
            }
        }
        $sql = "INSERT INTO {$table} (".implode(',', $cols).") VALUES (".implode(',', $vals).")";
        mysqli_query($conn, $sql);
    }
    if($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        mysqli_query($conn, "DELETE FROM {$table} WHERE REPLACE_PK={$id}");
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
$res = mysqli_query($conn, "SELECT * FROM {$table} ORDER BY created_at DESC");
?>
<!doctype html>
<html><head>
<link href="../style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="container py-4">
<h2><?php echo ucfirst('REPLACE_TITLE'); ?></h2>
<a href="../index.php" class="btn btn-secondary mb-3">Back</a>

<!-- Add form -->
<form method="post" class="mb-4">
  REPLACE_FORM_FIELDS
  <input type="hidden" name="action" value="add">
  <button class="btn btn-primary">Add</button>
</form>

<!-- Table -->
<table class="table table-striped">
<thead><tr>REPLACE_TABLE_HEADERS<th>Actions</th></tr></thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($res)){ ?>
<tr>
  REPLACE_TABLE_CELLS
  <td>
    <form method="post" style="display:inline">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="id" value="<?php echo $row['REPLACE_PK']; ?>">
      <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
    </form>
  </td>
</tr>
<?php } ?>
</tbody>
</table>
</body></html>
