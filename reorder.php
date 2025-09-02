<?php
session_start();
if (!isset($_SESSION['Username'])) { header('Location: signin.php'); exit(); }
if (($_SESSION['Role'] ?? '') !== 'admin') { header('Location: dash_staff.php'); exit(); }
$reorder = $_SESSION['reorder'] ?? 50;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $reorder = max(0, (int)($_POST['reorder'] ?? 0));
  $_SESSION['reorder'] = $reorder;
  header('Location: reorder.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="styles.css" rel="stylesheet" />
  <title>Set Reorder Point | IntelliWare</title>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .card { background:#1D387B; border:1px solid #2A4484; border-radius:10px; padding:14px; box-shadow: 0 0 20px 2px #304374; }
    .menu-item { border:1px solid #2A4484; color:#E3E3E3; border-radius:10px; padding:12px 16px; display:block; text-decoration:none; }
    .menu-item:hover { background:#13275B; }
    .btn { background:#51D55A; color:#fff; border-radius:6px; padding:8px 10px; font-size:12px; text-decoration:none; border:1px solid transparent; cursor:pointer; }
    .input { background:#13275B; color:#fff; border:1px solid #304374; padding:8px; border-radius:6px; width:100%; }
    .label { color:#E3E3E3; font-family:'Onest'; font-size:12px; margin-bottom:6px; display:block; }
  </style>
</head>
<body class="w-full h-screen bg-[#020A27] px-3 pt-3 flex items-start justify-center">
  <div class="w-full h-full flex flex-row rounded-t-[15px] overflow-hidden bg-gray-200 shadow-lg">
    <div class="sidebar w-[290px] bg-[#1D387B] text-white p-3 pt-5 flex flex-col">
      <div class="ml-2 mb-4"><img src="img/logo.svg" alt="IntelliWare" class="w-[180px]" /><div class="text-[10px]">Warehouse Inventory System</div></div>
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
        <div class="font-onest text-[20px] font-semibold">Set Reorder Point</div>
      </div>
      <div class="p-6" style="background:#0b1438; flex:1; overflow:auto;">
        <div class="card" style="max-width:520px;">
          <form method="POST">
            <label class="label">Reorder Point (units)</label>
            <input class="input" type="number" name="reorder" value="<?php echo (int)$reorder; ?>" min="0" />
            <div style="margin-top:10px;"><button class="btn" type="submit">Save</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>



