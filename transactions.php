<?php
session_start();
if (!isset($_SESSION['Username'])) { header('Location: signin.php'); exit(); }
$role = $_SESSION['Role'] ?? 'staff';
if (!in_array($role, ['admin','staff'])) { header('Location: dash_manager.php'); exit(); }

$items = $_SESSION['items'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'in';
    $id = (int)($_POST['id'] ?? 0);
    $qty = max(0, (int)($_POST['qty'] ?? 0));
    foreach ($items as &$it) {
        if ($it['id'] === $id) {
            if ($type === 'in') { $it['qty'] += $qty; }
            else { $it['qty'] = max(0, $it['qty'] - $qty); }
            break;
        }
    }
    unset($it);
    $_SESSION['items'] = $items;
    header('Location: transactions.php');
    exit();
}

$action = $_GET['action'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="styles.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Onest:wght@200;300;400;500;600;700&family=Overpass:wght@400;500;600;700&display=swap" rel="stylesheet">
  <title>Transactions | IntelliWare</title>
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
        <?php if ($role === 'admin'): ?>
          <a class="menu-item" href="dash_admin.php">Dashboard</a>
        <?php else: ?>
          <a class="menu-item" href="dash_staff.php">Dashboard</a>
        <?php endif; ?>
        <a class="menu-item" href="transactions.php">Transactions</a>
        <a class="menu-item" href="products.php">Products</a>
      </div>
      <div class="mt-auto p-2"><a class="btn" href="logout.php">Logout</a></div>
    </div>
    <div class="flex-1 flex flex-col">
      <div class="bg-white px-[50px] py-[20px] h-[67px] flex justify-between items-center" style="box-shadow: 0 3px 4px rgba(0,0,0,.3)">
        <div class="font-onest text-[20px] font-semibold">Stock In/Out</div>
        <div class="font-onest text-[14px]">Role: <?php echo htmlspecialchars(strtoupper($role)); ?></div>
      </div>
      <div class="p-6" style="background:#0b1438; flex:1; overflow:auto;">
        <div class="card">
          <div style="color:#E3E3E3; font-weight:600; font-family:'Overpass';">Create Transaction</div>
          <form method="POST" style="margin-top:8px; display:grid; grid-template-columns: repeat(5,1fr); gap:8px; align-items:end;">
            <div>
              <label class="label">Type</label>
              <select class="input" name="type">
                <option value="in" <?php echo $action==='in'?'selected':''; ?>>Stock In</option>
                <option value="out" <?php echo $action==='out'?'selected':''; ?>>Stock Out</option>
              </select>
            </div>
            <div>
              <label class="label">Item</label>
              <select class="input" name="id" required>
                <?php foreach($_SESSION['items'] ?? [] as $it): ?>
                  <option value="<?php echo $it['id']; ?>"><?php echo htmlspecialchars($it['sku'].' — '.$it['name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="label">Quantity</label>
              <input type="number" class="input" name="qty" value="0" min="0" required />
            </div>
            <div>
              <button class="btn" type="submit">Apply</button>
            </div>
          </form>
        </div>
        <div class="card" style="margin-top:16px;">
          <div style="color:#E3E3E3; font-weight:600; font-family:'Overpass';">Current Inventory</div>
          <div style="overflow:auto; margin-top:8px;">
            <table class="table">
              <thead><tr><th>SKU</th><th>Name</th><th>Qty</th><th>UoM</th><th>Location</th></tr></thead>
              <tbody>
                <?php foreach(($items) as $it): ?>
                <tr>
                  <td><?php echo htmlspecialchars($it['sku']); ?></td>
                  <td><?php echo htmlspecialchars($it['name']); ?></td>
                  <td><?php echo (int)$it['qty']; ?></td>
                  <td><?php echo htmlspecialchars($it['uom']); ?></td>
                  <td><?php echo htmlspecialchars($it['location']); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>


