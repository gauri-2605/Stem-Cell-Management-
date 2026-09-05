<?php
// Simple dashboard linking to modules
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Stem Cell DBMS - Dashboard</title>
  <link href="style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h1 class="mb-4">Stem Cell DBMS - Dashboard</h1>
  <div class="list-group">
    <a class="list-group-item list-group-item-action" href="modules/patients.php">Patients</a>
    <a class="list-group-item list-group-item-action" href="modules/donors.php">Donors</a>
    <a class="list-group-item list-group-item-action" href="modules/storage.php">Storage</a>
    <a class="list-group-item list-group-item-action" href="modules/staff.php">Staff</a>
    <a class="list-group-item list-group-item-action" href="modules/research.php">Research</a>
    <a class="list-group-item list-group-item-action" href="modules/inventory.php">Inventory</a>
  </div>
  <p class="mt-4"><small>Note: import <code>db/stemcell.sql</code> into your MySQL server first.</small></p>
</body>
</html>




