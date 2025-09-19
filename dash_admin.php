<?php
session_start();
if (!isset($_SESSION['Username'])) { header('Location: signin.php'); exit(); }
$role = $_SESSION['Role'] ?? '';
if ($role !== 'admin') {
  if ($role === 'manager') { header('Location: dash_manager.php'); exit(); }
  if ($role === 'staff') { header('Location: dash_staff.php'); exit(); }
  header('Location: signin.php'); exit();
}
$items = $_SESSION['items'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="styles.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Onest:wght@200;300;400;500;600;700&family=Overpass:wght@400;500;600;700&display=swap" rel="stylesheet">
  <title>Admin Dashboard | IntelliWare</title>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .card { background:#1D387B; border:1px solid #2A4484; border-radius:10px; padding:14px; box-shadow: 0 0 20px 2px #304374; }
    .metric { color:#E3E3E3; font-family:'Onest', sans-serif; font-size:12px; }
    .metric .num { font-size:22px; font-weight:700; font-family:'Overpass', sans-serif; }
    .menu-item { border:1px solid #2A4484; color:#E3E3E3; border-radius:10px; padding:12px 16px; display:block; text-decoration:none; }
    .menu-item:hover { background:#13275B; }
    .btn { background:#51D55A; color:#fff; border-radius:6px; padding:8px 10px; font-size:12px; text-decoration:none; }
  </style>
</head>
<body class="w-full h-screen bg-[#020A27] px-3 pt-3 flex items-start justify-center">
  <div class="w-full h-full flex flex-row rounded-t-[15px] overflow-hidden bg-gray-200 shadow-lg">
    <div class="sidebar w-[290px] bg-[#1D387B] text-white p-3 pt-5 flex flex-col">
      <div class="ml-2 mb-4">
        <img src="img/logo.svg" alt="IntelliWare" class="w-[220px]" />
      </div>
      <div class="p-2 flex flex-col gap-[8px]">
        <a class="menu-item" href="dash_admin.php">Dashboard</a>
        <a class="menu-item" href="products.php">Inventory</a>
        <a class="menu-item" href="reorder.php">Set Reorder Point</a>
        <a class="menu-item" href="transactions.php">Stock Transactions</a>
        <a class="menu-item" href="transaction_logs.php">Transaction Logs</a>
        <a class="menu-item" href="insights.php">Data Mining Insights</a>
        <a class="menu-item" href="reports.php">Reports</a>
        <a class="menu-item" href="users.php">User Management</a>
        <a class="menu-item" href="settings.php">Settings</a>
      </div>
      <div class="mt-auto p-2"><a class="btn" href="logout.php">Logout</a></div>
    </div>
    <div class="flex-1 flex flex-col">
      <div class="bg-white px-[50px] py-[20px] h-[67px] flex justify-between items-center" style="box-shadow: 0 3px 4px rgba(0,0,0,.3)">
        <div class="font-onest text-[20px] font-semibold">Admin Dashboard</div>
        <div class="font-onest text-[14px]">Welcome, <?php echo htmlspecialchars($_SESSION['Username']); ?></div>
      </div>
      <div class="p-6" style="background:#0b1438; flex:1; overflow:auto;">
        <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:16px;">
          <div class="card metric"><div>Total SKUs</div><div class="num"><?php echo count($items); ?></div></div>
          <div class="card metric"><div>Total Qty</div><div class="num"><?php echo array_sum(array_map(function($i){return (int)$i['qty'];}, $items)); ?></div></div>
          <div class="card metric"><div>Low Stock</div><div class="num"><?php echo count(array_filter($items,function($i){return (int)$i['qty']<50;})); ?></div></div>
          <div class="card metric"><div>Categories</div><div class="num"><?php echo count(array_unique(array_map(function($i){return $i['category'];}, $items))); ?></div></div>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-top:16px;">
          <div class="card"><div style="color:#E3E3E3; font-weight:600; font-family:'Overpass';">Quick Links</div>
            <div style="display:flex; gap:8px; margin-top:10px;">
              <a class="btn" href="products.php">Manage Products</a>
              <a class="btn" href="transactions.php">Stock In/Out</a>
              <a class="btn" href="reports.php">View Reports</a>
            </div>
          </div>
          <div class="card"><div style="color:#E3E3E3; font-weight:600; font-family:'Overpass';">AI Insights (placeholder)</div>
            <div class="metric" style="margin-top:8px;">Generate insights, forecasts, and anomaly detection here.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>


