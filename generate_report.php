<?php
ob_start();
session_start();
include 'config/db.php';
require('lib/fpdf/fpdf.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ======================
   FETCH DATA
====================== */

// Cycles
$cycle_sql = "SELECT * FROM cycles WHERE user_id = $user_id";
$cycle_result = $conn->query($cycle_sql);

// Symptoms
$sym_sql = "SELECT * FROM symptoms WHERE user_id = $user_id";
$sym_result = $conn->query($sym_sql);

/* ======================
   CREATE PDF
====================== */
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Health Report', 0, 1, 'C');

$pdf->Ln(5);

/* ======================
   CYCLE DATA
====================== */
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Cycle History', 0, 1);

$pdf->SetFont('Arial', '', 10);

while($row = $cycle_result->fetch_assoc()){
    $pdf->Cell(0, 8, "Start: {$row['start_date']}  End: {$row['end_date']}  Length: {$row['cycle_length']} days", 0, 1);
}

$pdf->Ln(5);

/* ======================
   SYMPTOMS DATA
====================== */
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Symptoms & Mood', 0, 1);

$pdf->SetFont('Arial', '', 10);

while($row = $sym_result->fetch_assoc()){
    $pdf->Cell(0, 8, "Date: {$row['date']}  Mood: {$row['mood']}  Symptoms: {$row['symptoms']}", 0, 1);
}

/* ======================
   OUTPUT PDF
====================== */
$pdf->Output('D', 'health_report.pdf');
ob_end_flush();
?>