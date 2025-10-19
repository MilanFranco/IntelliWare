<?php
// Database Setup Script for IntelliWare
// Run this script once to set up the database with sample data

require_once 'config/db.php';

echo "<h2>Setting up IntelliWare Database...</h2>";

// Check if database exists, if not create it
$createDbQuery = "CREATE DATABASE IF NOT EXISTS warehouse_system";
if ($conn->query($createDbQuery)) {
    echo "✅ Database 'warehouse_system' created/verified<br>";
} else {
    echo "❌ Error creating database: " . $conn->error . "<br>";
}

// Use the database
$conn->query("USE warehouse_system");

// Create tables
$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','manager','staff') NOT NULL,
        full_name VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "products" => "CREATE TABLE IF NOT EXISTS products (
        product_id INT AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(100) NOT NULL,
        category VARCHAR(50),
        supplier VARCHAR(100),
        quantity INT DEFAULT 0,
        reorder_point INT DEFAULT 10,
        unit VARCHAR(20),
        expiry_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "transactions" => "CREATE TABLE IF NOT EXISTS transactions (
        transaction_id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT,
        user_id INT,
        transaction_type ENUM('stock_in','stock_out') NOT NULL,
        quantity INT NOT NULL,
        transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        remarks VARCHAR(255),
        FOREIGN KEY (product_id) REFERENCES products(product_id),
        FOREIGN KEY (user_id) REFERENCES users(user_id)
    )",
    
    "logs" => "CREATE TABLE IF NOT EXISTS logs (
        log_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        action VARCHAR(255),
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id)
    )",
    
    "patterns" => "CREATE TABLE IF NOT EXISTS patterns (
        pattern_id INT AUTO_INCREMENT PRIMARY KEY,
        description VARCHAR(255),
        confidence DECIMAL(5,2),
        support DECIMAL(5,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "forecast" => "CREATE TABLE IF NOT EXISTS forecast (
        forecast_id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT,
        forecast_date DATE,
        predicted_qty INT,
        model_used VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(product_id)
    )"
];

foreach ($tables as $tableName => $createQuery) {
    if ($conn->query($createQuery)) {
        echo "✅ Table '$tableName' created/verified<br>";
    } else {
        echo "❌ Error creating table '$tableName': " . $conn->error . "<br>";
    }
}

// Insert sample users
$users = [
    ['admin', password_hash('admin123', PASSWORD_DEFAULT), 'admin', 'System Administrator'],
    ['manager', password_hash('manager123', PASSWORD_DEFAULT), 'manager', 'Warehouse Manager'],
    ['staff', password_hash('staff123', PASSWORD_DEFAULT), 'staff', 'Warehouse Staff']
];

$userQuery = "INSERT IGNORE INTO users (username, password, role, full_name) VALUES (?, ?, ?, ?)";
$userStmt = $conn->prepare($userQuery);

foreach ($users as $user) {
    $userStmt->bind_param("ssss", $user[0], $user[1], $user[2], $user[3]);
    if ($userStmt->execute()) {
        echo "✅ User '{$user[0]}' created/verified<br>";
    } else {
        echo "❌ Error creating user '{$user[0]}': " . $userStmt->error . "<br>";
    }
}
$userStmt->close();

// Insert sample products
$products = [
    ['Wood Pallet', 'Packaging', 'Timber Co.', 120, 20, 'pcs', '2025-12-31'],
    ['Bolt M8x40', 'Hardware', 'Hardware Supply', 860, 50, 'pcs', '2026-06-30'],
    ['Thermal Label 4x6', 'Labels', 'Label Solutions', 540, 100, 'roll', '2025-08-15'],
    ['Steel Bracket', 'Hardware', 'Metal Works', 45, 10, 'pcs', '2026-03-20'],
    ['Cardboard Box', 'Packaging', 'Packaging Plus', 200, 50, 'pcs', '2025-10-10']
];

$productQuery = "INSERT IGNORE INTO products (product_name, category, supplier, quantity, reorder_point, unit, expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
$productStmt = $conn->prepare($productQuery);

foreach ($products as $product) {
    $productStmt->bind_param("sssiiss", $product[0], $product[1], $product[2], $product[3], $product[4], $product[5], $product[6]);
    if ($productStmt->execute()) {
        echo "✅ Product '{$product[0]}' created/verified<br>";
    } else {
        echo "❌ Error creating product '{$product[0]}': " . $productStmt->error . "<br>";
    }
}
$productStmt->close();

// Insert sample transactions
$transactions = [
    [1, 1, 'stock_in', 50, 'Initial stock'],
    [2, 1, 'stock_in', 100, 'Bulk order'],
    [3, 1, 'stock_in', 200, 'New supplier'],
    [1, 2, 'stock_out', 10, 'Production use'],
    [2, 2, 'stock_out', 25, 'Maintenance']
];

$transactionQuery = "INSERT IGNORE INTO transactions (product_id, user_id, transaction_type, quantity, remarks) VALUES (?, ?, ?, ?, ?)";
$transactionStmt = $conn->prepare($transactionQuery);

foreach ($transactions as $transaction) {
    $transactionStmt->bind_param("iisis", $transaction[0], $transaction[1], $transaction[2], $transaction[3], $transaction[4]);
    if ($transactionStmt->execute()) {
        echo "✅ Transaction recorded<br>";
    } else {
        echo "❌ Error recording transaction: " . $transactionStmt->error . "<br>";
    }
}
$transactionStmt->close();

echo "<h3>Database setup completed!</h3>";
echo "<p><strong>Default Login Credentials:</strong></p>";
echo "<ul>";
echo "<li><strong>Admin:</strong> username: admin, password: admin123</li>";
echo "<li><strong>Manager:</strong> username: manager, password: manager123</li>";
echo "<li><strong>Staff:</strong> username: staff, password: staff123</li>";
echo "</ul>";

$conn->close();
?>
