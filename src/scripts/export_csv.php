<?php
session_start();

if (!isset($_SESSION['Username'])) { header('Location: ../../signin.php'); exit(); }
$role = $_SESSION['Role'] ?? '';
if (!in_array($role, ['admin','manager','staff'])) { header('Location: ../../signin.php'); exit(); }

$items = $_SESSION['items'] ?? [];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=inventory_'.date('Y-m-d_His').'.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['SKU', 'Name', 'Category', 'Qty', 'UoM', 'Location']);
foreach ($items as $it) {
    fputcsv($output, [
        (string)($it['sku'] ?? ''),
        (string)($it['name'] ?? ''),
        (string)($it['category'] ?? ''),
        (int)($it['qty'] ?? 0),
        (string)($it['uom'] ?? ''),
        (string)($it['location'] ?? ''),
    ]);
}
fclose($output);
exit();
?>


