<?php
session_start();
if (!isset($_SESSION['Username'])) { header('Location: signin.php'); exit(); }
$role = $_SESSION['Role'] ?? 'staff';
// For demo, re-use items as faux logs
$logs = $_SESSION['logs'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="styles.css" rel="stylesheet" />
  <title>Transaction Logs | IntelliWare</title>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .card { background:#1D387B; border:1px solid #2A4484; border-radius:10px; padding:14px; box-shadow: 0 0 20px 2px #304374; }
    .menu-item { border:1px solid #2A4484; color:#E3E3E3; border-radius:10px; padding:12px 16px; display:block; text-decoration:none; }
    .menu-item:hover { background:#13275B; }
    .btn { background:#51D55A; color:#fff; border-radius:6px; padding:8px 10px; font-size:12px; text-decoration:none; border:1px solid transparent; cursor:pointer; }
    .table { width:100%; border-collapse:collapse; }
    .table th, .table td { padding:10px; border-bottom:1px solid #2A4484; color:#E3E3E3; font-family:'Onest'; font-size:12px; }
  </style>
</head>
<body class="w-full h-screen bg-[#020A27] px-3 pt-3 flex items-start justify-center">
  <div class="w-full h-full flex flex-row rounded-t-[15px] overflow-hidden bg-gray-200 shadow-lg">
    <div class="sidebar w-[290px] bg-[#1D387B] text-white p-3 pt-5 flex flex-col">
      <div class="ml-2 mb-4"><img src="img/logo.svg" alt="IntelliWare" class="w-[180px]" /><div class="text-[10px]">Warehouse Inventory System</div></div>
      <div class="p-2 flex flex-col gap-[8px]">
        <?php if ($role==='admin'): ?>
          <a class="menu-item" href="dash_admin.php">Dashboard</a>
          <a class="menu-item" href="transaction_logs.php">Transaction Logs</a>
        <?php else: ?>
          <a class="menu-item" href="dash_staff.php">Dashboard</a>
          <a class="menu-item" href="transaction_logs.php">My Transaction History</a>
        <?php endif; ?>
      </div>
      <div class="mt-auto p-2"><a class="btn" href="logout.php">Logout</a></div>
    </div>
    <div class="flex-1 flex flex-col">
      <div class="bg-white px-[50px] py-[20px] h-[67px] flex justify-between items-center" style="box-shadow: 0 3px 4px rgba(0,0,0,.3)">
        <div class="font-onest text-[20px] font-semibold">Transaction Logs</div>
      </div>
      <div class="p-6" style="background:#0b1438; flex:1; overflow:auto;">
        <div class="card">
          <table class="table">
            <thead><tr><th>Date</th><th>User</th><th>Type</th><th>Item</th><th>Qty</th></tr></thead>
            <tbody>
              <?php if (empty($logs)) { echo '<tr><td colspan="5" style="color:#9aa6d1;">No logs yet.</td></tr>'; } ?>
              <?php foreach($logs as $row): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['date'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($row['user'] ?? $_SESSION['Username']); ?></td>
                  <td><?php echo htmlspecialchars($row['type'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($row['item'] ?? '-'); ?></td>
                  <td><?php echo (int)($row['qty'] ?? 0); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>



