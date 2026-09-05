<?php
// you can include db_connect.php if needed
// include("config/db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stem Cell Database Management System</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f5f8fa;
    }
    header {
      background: #0066cc;
      color: white;
      padding: 15px;
      text-align: center;
    }
    nav {
      display: flex;
      justify-content: center;
      background: #004080;
      padding: 10px;
    }
    nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
    }
    nav a:hover {
      text-decoration: underline;
    }
    .container {
      text-align: center;
      margin-top: 50px;
    }
    .cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      background: white;
      padding: 20px;
      width: 200px;
      border-radius: 10px;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 6px 12px rgba(0,0,0,0.2);
    }
    .card a {
      text-decoration: none;
      color: #0066cc;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <header>
    <h1>Stem Cell Database Management System</h1>
    <p>Welcome to StemCellDBMS Portal</p>
  </header>

  <nav>
    <a href="home.php">Home</a>
    <a href="modules/patients.php">Patients</a>
    <a href="modules/donors.php">Donors</a>
    <a href="modules/staff.php">Staff</a>
    <a href="modules/research.php">Research</a>
    <a href="modules/inventory.php">Inventory</a>
    <a href="modules/storage.php">Storage</a>
    <a href="modules/reports.php">Reports</a>
  <a href="modules/chatbot.php">Awareness / Chatbot</a>
  </nav>

  <div class="container">
    <h2>Dashboard</h2>
    <div class="cards">
      <div class="card"><a href="modules/patients.php">👨‍⚕️ Manage Patients</a></div>
      <div class="card"><a href="modules/donors.php">🧬 Manage Donors</a></div>
      <div class="card"><a href="modules/staff.php">👩‍🔬 Manage Staff</a></div>
      <div class="card"><a href="modules/research.php">📑 Research Data</a></div>
      <div class="card"><a href="modules/inventory.php">📦 Inventory</a></div>
  <div class="card"><a href="modules/storage.php">❄️ Storage</a></div>
  <div class="card"><a href="modules/chatbot.php">💬 Awareness / Chatbot</a></div>
  <div class="card"><a href="modules/more_information.php">ℹ️ More Information</a></div>
  <div class="card"><a href="modules/reports.php">📊 Reports</a></div>
    </div>
  </div>

</body>
</html>

