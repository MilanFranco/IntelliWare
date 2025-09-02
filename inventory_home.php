<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header('Location: signin.php');
    exit();
}

// Handle CRUD actions (session-based)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $items = $_SESSION['items'] ?? [];
        $nextId = 1;
        foreach ($items as $it) { $nextId = max($nextId, $it['id'] + 1); }
        $items[] = [
            'id' => $nextId,
            'sku' => trim($_POST['sku'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'qty' => (int)($_POST['qty'] ?? 0),
            'uom' => trim($_POST['uom'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
        ];
        $_SESSION['items'] = $items;
        header('Location: inventory_home.php');
        exit();
    }
    if ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $items = $_SESSION['items'] ?? [];
        foreach ($items as &$it) {
            if ($it['id'] === $id) {
                $it['sku'] = trim($_POST['sku'] ?? $it['sku']);
                $it['name'] = trim($_POST['name'] ?? $it['name']);
                $it['category'] = trim($_POST['category'] ?? $it['category']);
                $it['qty'] = (int)($_POST['qty'] ?? $it['qty']);
                $it['uom'] = trim($_POST['uom'] ?? $it['uom']);
                $it['location'] = trim($_POST['location'] ?? $it['location']);
                break;
            }
        }
        unset($it);
        $_SESSION['items'] = $items;
        header('Location: inventory_home.php');
        exit();
    }
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $items = $_SESSION['items'] ?? [];
        $items = array_values(array_filter($items, function($it) use ($id) { return $it['id'] !== $id; }));
        $_SESSION['items'] = $items;
        header('Location: inventory_home.php');
        exit();
    }
}

$username = $_SESSION['Username'];
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
    <title>Home | IntelliWare</title>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .menu-item { border: 1px solid #2A4484; transition: all .2s ease; }
        .menu-item:hover { background:#1D387B; transform: translateY(-1px); }
        .table { width:100%; border-collapse: collapse; }
        .table th, .table td { padding:10px; border-bottom:1px solid #2A4484; color:#E3E3E3; font-family:'Onest', sans-serif; font-size:12px; }
        .badge { padding:2px 8px; border-radius:999px; background:#13275B; color:#E3E3E3; font-size:11px; }
        .btn { background:#51D55A; color:#fff; border:1px solid transparent; border-radius:6px; padding:8px 10px; font-size:12px; cursor:pointer; }
        .btn:hover { background:#3d9743; }
        .btn-outline { background:transparent; border:1px solid #2A4484; color:#E3E3E3; }
        .input { background:#13275B; color:#fff; border:1px solid #304374; padding:8px; border-radius:6px; width:100%; }
        .label { color:#E3E3E3; font-family:'Onest', sans-serif; font-size:12px; margin-bottom:6px; display:block; }
        .card { background:#1D387B; border:1px solid #2A4484; border-radius:10px; padding:14px; box-shadow: 0 0 20px 2px #304374; }
        .sidebar { background:#1D387B; }
        .header-bar { background:#fff; }
    </style>
    <script>
        function fillEdit(id, sku, name, category, qty, uom, location) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-sku').value = sku;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-category').value = category;
            document.getElementById('edit-qty').value = qty;
            document.getElementById('edit-uom').value = uom;
            document.getElementById('edit-location').value = location;
            document.getElementById('edit-modal').style.display = 'block';
        }
        function closeEdit(){ document.getElementById('edit-modal').style.display = 'none'; }
    </script>
    </head>
<body id="mainBody" class="w-full h-screen bg-[#020A27] px-3 pt-3 flex items-start justify-center">

    <div class="w-full h-full flex flex-row rounded-t-[15px] overflow-hidden bg-gray-200 shadow-lg">
        <div id="sidebar" class="sidebar w-[290px] text-white p-3 pt-5 flex flex-col">
            <div class="text-left leading-tight mb-4 ml-2 font-onest flex items-center justify-between">
                <div class="flex flex-col items-start">
                    <img src="img/logo.svg" alt="IntelliWare" class="w-[180px]" id="logo" />
                    <p class="text-[10px] font-light" id="logo-text">Warehouse Inventory System</p>
                </div>
            </div>
            <div class="p-2 flex flex-col gap-[8px]">
                <a href="#" class="bg-[#13275B] menu-item flex items-center px-7 py-3 h-[53px] text-[16px] font-onest text-[#E3E3E3] font-[400] rounded-[10px]">Dashboard</a>
                <a href="#" class="menu-item flex items-center px-7 py-3 h-[53px] text-[16px] font-onest text-[#E3E3E3] font-[400] rounded-[10px]">Items</a>
                <a href="#" class="menu-item flex items-center px-7 py-3 h-[53px] text-[16px] font-onest text-[#E3E3E3] font-[400] rounded-[10px]">Suppliers</a>
                <a href="#" class="menu-item flex items-center px-7 py-3 h-[53px] text-[16px] font-onest text-[#E3E3E3] font-[400] rounded-[10px]">Inbound/Outbound</a>
            </div>
            <div class="mt-auto text-white px-4 font-onest py-3 rounded-md text-lg font-regular flex items-center justify-between w-full"></div>
            <div class="sidebar-footer relative rounded-md m-0 w-full text-center font-overpass font-light text-[10px]  px-2 text-gray-400 mt-2 my-0 py-2">
                <hr class="border-t border-[#314f9b] w-full mx-auto mb-2" />
                © 2025 IntelliWare. All rights reserved.
            </div>
        </div>

        <div class="main-content flex-1 flex flex-col h-full ">
            <div class="header-bar bg-white px-[50px] py-[20px] h-[67px] flex justify-between items-center w-full" style="box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.3);">
                <div class="font-onest text-[20px] font-semibold" style="letter-spacing: -0.03em;">Inventory Dashboard</div>
                <div class="font-onest text-[14px]">Welcome, <?php echo htmlspecialchars($username); ?></div>
            </div>

            <div class="p-6 overflow-y-auto" style="background:#0b1438; flex:1;">
                <div class="grid" style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                    <div class="card">
                        <div class="font-overpass" style="color:#E3E3E3; font-weight:600; margin-bottom:8px;">Add Item</div>
                        <form method="POST">
                            <input type="hidden" name="action" value="add" />
                            <div class="grid" style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                                <div>
                                    <label class="label">SKU</label>
                                    <input class="input" name="sku" required />
                                </div>
                                <div>
                                    <label class="label">Name</label>
                                    <input class="input" name="name" required />
                                </div>
                                <div>
                                    <label class="label">Category</label>
                                    <input class="input" name="category" />
                                </div>
                                <div>
                                    <label class="label">Quantity</label>
                                    <input type="number" class="input" name="qty" value="0" min="0" />
                                </div>
                                <div>
                                    <label class="label">UoM</label>
                                    <input class="input" name="uom" placeholder="pcs, box, roll" />
                                </div>
                                <div>
                                    <label class="label">Location</label>
                                    <input class="input" name="location" placeholder="A1" />
                                </div>
                            </div>
                            <div style="margin-top:10px;">
                                <button class="btn" type="submit">Add Item</button>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="font-overpass" style="color:#E3E3E3; font-weight:600; margin-bottom:8px;">Summary</div>
                        <div style="color:#E3E3E3; font-family:'Onest', sans-serif; font-size:13px;">
                            Total SKUs: <span class="badge"><?php echo count($items); ?></span>
                            <br />
                            Total Qty: <span class="badge"><?php echo array_sum(array_map(function($i){return (int)$i['qty'];}, $items)); ?></span>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top:16px;">
                    <div class="font-overpass" style="color:#E3E3E3; font-weight:600; margin-bottom:8px;">Items</div>
                    <div style="overflow:auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Qty</th>
                                    <th>UoM</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $it): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($it['sku']); ?></td>
                                    <td><?php echo htmlspecialchars($it['name']); ?></td>
                                    <td><?php echo htmlspecialchars($it['category']); ?></td>
                                    <td><?php echo (int)$it['qty']; ?></td>
                                    <td><?php echo htmlspecialchars($it['uom']); ?></td>
                                    <td><?php echo htmlspecialchars($it['location']); ?></td>
                                    <td>
                                        <button class="btn btn-outline" onclick="fillEdit(<?php echo $it['id']; ?>,'<?php echo htmlspecialchars($it['sku'], ENT_QUOTES); ?>','<?php echo htmlspecialchars($it['name'], ENT_QUOTES); ?>','<?php echo htmlspecialchars($it['category'], ENT_QUOTES); ?>',<?php echo (int)$it['qty']; ?>,'<?php echo htmlspecialchars($it['uom'], ENT_QUOTES); ?>','<?php echo htmlspecialchars($it['location'], ENT_QUOTES); ?>')">Edit</button>
                                        <form method="POST" style="display:inline-block; margin-left:6px;" onsubmit="return confirm('Delete this item?');">
                                            <input type="hidden" name="action" value="delete" />
                                            <input type="hidden" name="id" value="<?php echo $it['id']; ?>" />
                                            <button class="btn btn-outline" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($items)): ?>
                                <tr><td colspan="7" style="color:#9aa6d1;">No items yet. Add your first item.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); align-items:center; justify-content:center;">
        <div class="card" style="width:600px; margin:40px auto; background:#1D387B;">
            <div class="font-overpass" style="color:#E3E3E3; font-weight:600; margin-bottom:8px;">Edit Item</div>
            <form method="POST">
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" name="id" id="edit-id" />
                <div class="grid" style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                    <div>
                        <label class="label">SKU</label>
                        <input id="edit-sku" class="input" name="sku" />
                    </div>
                    <div>
                        <label class="label">Name</label>
                        <input id="edit-name" class="input" name="name" />
                    </div>
                    <div>
                        <label class="label">Category</label>
                        <input id="edit-category" class="input" name="category" />
                    </div>
                    <div>
                        <label class="label">Quantity</label>
                        <input type="number" id="edit-qty" class="input" name="qty" />
                    </div>
                    <div>
                        <label class="label">UoM</label>
                        <input id="edit-uom" class="input" name="uom" />
                    </div>
                    <div>
                        <label class="label">Location</label>
                        <input id="edit-location" class="input" name="location" />
                    </div>
                </div>
                <div style="margin-top:10px; display:flex; gap:8px;">
                    <button class="btn" type="submit">Save</button>
                    <button type="button" class="btn btn-outline" onclick="closeEdit()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>


