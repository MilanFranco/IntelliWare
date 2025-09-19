<?php
session_start();

if (!isset($_SESSION['Username'])) { header('Location: ../../signin.php'); exit(); }
$role = $_SESSION['Role'] ?? '';
if (!in_array($role, ['admin','manager','staff'])) { header('Location: ../../signin.php'); exit(); }

require_once __DIR__.'/lib/fpdf.php';

$items = $_SESSION['items'] ?? [];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Cell(0, 10, 'Inventory Report', 0, 1);
$pdf->Ln(2);
$pdf->Cell(0, 10, 'Generated: '.date('Y-m-d H:i:s'), 0, 1);
$pdf->Ln(4);

$pdf->Cell(0, 10, 'SKU | Name | Category | Qty | UoM | Location', 0, 1);
foreach ($items as $it) {
    $row = ($it['sku'] ?? '').' | '.($it['name'] ?? '').' | '.($it['category'] ?? '').' | '.(int)($it['qty'] ?? 0).' | '.($it['uom'] ?? '').' | '.($it['location'] ?? '');
    $pdf->Cell(0, 10, $row, 0, 1);
}

$pdf->Output('D', 'inventory_'.date('Y-m-d_His').'.pdf');
exit();
?>


