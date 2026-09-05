<?php
// Correct paths since reports.php is in modules folder
include('../config/db_connect.php');  
require('../fpdf/fpdf.php/fpdf.php');
// Fetch summary data
$total_patients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM patients"))["total"];
$total_donors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM donors"))["total"];
$total_storage = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM storage"))["total"];
$total_staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff"))["total"];
$total_research = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM research"))["total"];
$total_inventory = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM inventory"))["total"];

// Breakdown by blood group for patients and donors
$patient_bg = mysqli_query($conn, "SELECT blood_group, COUNT(*) AS count FROM patients GROUP BY blood_group");
$donor_bg = mysqli_query($conn, "SELECT blood_group, COUNT(*) AS count FROM donors GROUP BY blood_group");

// Recent research projects
$recent_research = mysqli_query($conn, "SELECT project_name, lead_scientist, start_date, status FROM research ORDER BY start_date DESC LIMIT 5");

// Inventory summary
$inventory_items = mysqli_query($conn, "SELECT item_name, quantity, unit FROM inventory ORDER BY quantity DESC LIMIT 5");

if (isset($_POST['download_pdf'])) {
  // Create PDF
  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',16);
  $pdf->Cell(0,10,'Stem Cell Database Advanced Report',0,1,'C');
  $pdf->Ln(10);

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,10,"Summary:",0,1);
  $pdf->SetFont('Arial','',12);
  $pdf->Cell(0,10,"Total Patients: $total_patients",0,1);
  $pdf->Cell(0,10,"Total Donors: $total_donors",0,1);
  $pdf->Cell(0,10,"Total Storage Units: $total_storage",0,1);
  $pdf->Cell(0,10,"Total Staff: $total_staff",0,1);
  $pdf->Cell(0,10,"Total Research Projects: $total_research",0,1);
  $pdf->Cell(0,10,"Total Inventory Items: $total_inventory",0,1);
  $pdf->Ln(10);

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,10,"Patients by Blood Group:",0,1);
  $pdf->SetFont('Arial','',12);
  while($row = mysqli_fetch_assoc($patient_bg)) {
    $pdf->Cell(0,10,$row['blood_group'].": ".$row['count'],0,1);
  }
  $pdf->Ln(5);
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,10,"Donors by Blood Group:",0,1);
  $pdf->SetFont('Arial','',12);
  while($row = mysqli_fetch_assoc($donor_bg)) {
    $pdf->Cell(0,10,$row['blood_group'].": ".$row['count'],0,1);
  }
  $pdf->Ln(10);

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,10,"Recent Research Projects:",0,1);
  $pdf->SetFont('Arial','',12);
  while($row = mysqli_fetch_assoc($recent_research)) {
    $pdf->Cell(0,10,$row['project_name']." (".$row['lead_scientist'].", ".$row['start_date'].", ".$row['status'].")",0,1);
  }
  $pdf->Ln(10);

  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,10,"Top Inventory Items:",0,1);
  $pdf->SetFont('Arial','',12);
  while($row = mysqli_fetch_assoc($inventory_items)) {
    $pdf->Cell(0,10,$row['item_name'].": ".$row['quantity']." ".$row['unit'],0,1);
  }
  $pdf->Ln(10);

  $pdf->Cell(0,10,"Generated on: ".date("Y-m-d H:i:s"),0,1);
  $pdf->Output("D", "StemCell_Advanced_Report.pdf");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports | Stem Cell DB</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      padding: 20px;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 60%;
      margin: auto;
    }
    h1 { text-align: center; color: #333; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    th { background-color: #4CAF50; color: white; }
    .btn {
      display: inline-block;
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 20px;
      cursor: pointer;
      border: none;
    }
    .btn:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Stem Cell Database - Advanced Reports</h1>
    <table>
      <tr><th>Category</th><th>Count</th></tr>
      <tr><td>Total Patients</td><td><?php echo $total_patients; ?></td></tr>
      <tr><td>Total Donors</td><td><?php echo $total_donors; ?></td></tr>
      <tr><td>Total Storage Units</td><td><?php echo $total_storage; ?></td></tr>
      <tr><td>Total Staff</td><td><?php echo $total_staff; ?></td></tr>
      <tr><td>Total Research Projects</td><td><?php echo $total_research; ?></td></tr>
      <tr><td>Total Inventory Items</td><td><?php echo $total_inventory; ?></td></tr>
    </table>

    <h2>Patients by Blood Group</h2>
    <table>
      <tr><th>Blood Group</th><th>Count</th></tr>
      <?php while($row = mysqli_fetch_assoc($patient_bg)) { ?>
        <tr><td><?php echo $row['blood_group']; ?></td><td><?php echo $row['count']; ?></td></tr>
      <?php } ?>
    </table>

    <h2>Donors by Blood Group</h2>
    <table>
      <tr><th>Blood Group</th><th>Count</th></tr>
      <?php mysqli_data_seek($donor_bg, 0); while($row = mysqli_fetch_assoc($donor_bg)) { ?>
        <tr><td><?php echo $row['blood_group']; ?></td><td><?php echo $row['count']; ?></td></tr>
      <?php } ?>
    </table>

    <h2>Recent Research Projects</h2>
    <table>
      <tr><th>Project Name</th><th>Lead Scientist</th><th>Start Date</th><th>Status</th></tr>
      <?php while($row = mysqli_fetch_assoc($recent_research)) { ?>
        <tr><td><?php echo $row['project_name']; ?></td><td><?php echo $row['lead_scientist']; ?></td><td><?php echo $row['start_date']; ?></td><td><?php echo $row['status']; ?></td></tr>
      <?php } ?>
    </table>

    <h2>Top Inventory Items</h2>
    <table>
      <tr><th>Item Name</th><th>Quantity</th><th>Unit</th></tr>
      <?php while($row = mysqli_fetch_assoc($inventory_items)) { ?>
        <tr><td><?php echo $row['item_name']; ?></td><td><?php echo $row['quantity']; ?></td><td><?php echo $row['unit']; ?></td></tr>
      <?php } ?>
    </table>

    <form method="post">
      <button type="submit" name="download_pdf" class="btn">Download Advanced PDF Report</button>
    </form>
  </div>
</body>
</html>
