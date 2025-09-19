<?php
session_start();
if (!isset($_SESSION['Username'])) { header('Location: signin.php'); exit(); }
$role = $_SESSION['Role'] ?? 'staff';
if (!in_array($role, ['admin','manager'])) { header('Location: dash_staff.php'); exit(); }
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
  <title>Reports | IntelliWare</title>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .card { background:#1D387B; border:1px solid #2A4484; border-radius:10px; padding:14px; box-shadow: 0 0 20px 2px #304374; }
    .menu-item { border:1px solid #2A4484; color:#E3E3E3; border-radius:10px; padding:12px 16px; display:block; text-decoration:none; }
    .menu-item:hover { background:#13275B; }
    .btn { background:#51D55A; color:#fff; border-radius:6px; padding:8px 10px; font-size:12px; text-decoration:none; border:1px solid transparent; cursor:pointer; }
    .input { background:#13275B; color:#fff; border:1px solid #304374; padding:8px; border-radius:6px; width:100%; }
    .label { color:#E3E3E3; font-family:'Onest'; font-size:12px; margin-bottom:6px; display:block; }
    .table { width:100%; border-collapse:collapse; }
    .table th, .table td { padding:10px; border-bottom:1px solid #2A4484; color:#E3E3E3; font-family:'Onest'; font-size:12px; }
  </style>
</head>
<body class="w-full h-screen bg-[#020A27] px-3 pt-3 flex items-start justify-center">
  <div class="w-full h-full flex flex-row rounded-t-[15px] overflow-hidden bg-gray-200 shadow-lg">
    <div class="sidebar w-[290px] bg-[#1D387B] text-white p-3 pt-5 flex flex-col">
      <div class="ml-2 mb-4">
        <img src="img/logo.svg" alt="IntelliWare" class="w-[180px]" />
        <div class="text-[10px]">Warehouse Inventory System</div>
      </div>
      <div class="p-2 flex flex-col gap-[8px]">
        <a class="menu-item" href="<?php echo $role==='admin'?'dash_admin.php':'dash_manager.php'; ?>">Dashboard</a>
        <a class="menu-item" href="reports.php">Reports</a>
        <a class="menu-item" href="insights.php">Insights</a>
      </div>
      <div class="mt-auto p-2"><a class="btn" href="logout.php">Logout</a></div>
    </div>
    <div class="flex-1 flex flex-col">
      <div class="bg-white px-[50px] py-[20px] h-[67px] flex justify-between items-center" style="box-shadow: 0 3px 4px rgba(0,0,0,.3)">
        <div class="font-onest text-[20px] font-semibold">Reports</div>
        <div class="font-onest text-[14px]">Role: <?php echo htmlspecialchars(strtoupper($role)); ?></div>
      </div>
      <div class="p-6" style="background:#0b1438; flex:1; overflow:auto;">
        <div class="card">
          <div style="color:#E3E3E3; font-weight:600; font-family:'Overpass';">Filters</div>
          <form style="display:grid; grid-template-columns: repeat(4,1fr); gap:8px; margin-top:8px;">
            <div><label class="label">Date From</label><input type="date" class="input" /></div>
            <div><label class="label">Date To</label><input type="date" class="input" /></div>
            <div><label class="label">Category</label><input class="input" placeholder="All" /></div>
            <div style="display:flex; align-items:flex-end;"><button class="btn" type="button">Generate</button></div>
          </form>
        </div>
        <div class="card" style="margin-top:16px;">
          <div style="color:#E3E3E3; font-weight:600; font-family:'Overpass';">Stock Snapshot</div>
          <table class="table" style="margin-top:8px;">
            <thead><tr><th>SKU</th><th>Name</th><th>Category</th><th>Qty</th></tr></thead>
            <tbody>
              <?php foreach($items as $it): ?>
                <tr>
                  <td><?php echo htmlspecialchars($it['sku']); ?></td>
                  <td><?php echo htmlspecialchars($it['name']); ?></td>
                  <td><?php echo htmlspecialchars($it['category']); ?></td>
                  <td><?php echo (int)$it['qty']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div style="margin-top:10px; display:flex; gap:8px;">
            <a class="btn" href="src/scripts/export_pdf.php">Download PDF</a>
            <a class="btn" href="src/scripts/export_csv.php">Download Excel</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>


