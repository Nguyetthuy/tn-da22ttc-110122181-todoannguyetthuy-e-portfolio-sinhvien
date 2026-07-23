<?php
$db = 'capabet_portfolio';
$user = 'root';
$pass = '';

$hosts = ['127.0.0.1', 'localhost'];
$ports = ['3306', '3307', '3308'];

$pdo = null;
foreach ($hosts as $host) {
    foreach ($ports as $port) {
        try {
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            echo "Connected successfully to host=$host, port=$port, db=$db\n";
            break 2;
        } catch (\Exception $e) {
            // echo "Failed host=$host port=$port: " . $e->getMessage() . "\n";
        }
    }
}

if (!$pdo) {
    die("ERROR: Could not connect to database on any host/port.\n");
}

echo "=== KHOI_KIEN_THUC ===\n";
try {
    $rows = $pdo->query("SELECT * FROM KHOI_KIEN_THUC LIMIT 10")->fetchAll();
    foreach ($rows as $r) {
        echo "maKhoiKT: {$r['maKhoiKT']} | tenKhoiKT: {$r['tenKhoiKT']}\n";
    }
} catch (\Exception $e) {
    echo "Error KHOI_KIEN_THUC: " . $e->getMessage() . "\n";
}

echo "\n=== nhom_cdr_ct_daotao ===\n";
try {
    $rows = $pdo->query("SELECT * FROM nhom_cdr_ct_daotao LIMIT 10")->fetchAll();
    foreach ($rows as $r) {
        echo "maNhomDR: {$r['maNhomDR']} | tenNhomDR: {$r['tenNhomDR']}\n";
    }
} catch (\Exception $e) {
    echo "Error nhom_cdr_ct_daotao: " . $e->getMessage() . "\n";
}

echo "\n=== ct_khoi_kien_thuc ===\n";
try {
    $rows = $pdo->query("SELECT * FROM ct_khoi_kien_thuc LIMIT 10")->fetchAll();
    foreach ($rows as $r) {
        echo "maCTKhoiKT: {$r['maCTKhoiKT']} | tenCTKhoiKT: {$r['tenCTKhoiKT']} | maKhoiKT: {$r['maKhoiKT']}\n";
    }
} catch (\Exception $e) {
    echo "Error ct_khoi_kien_thuc: " . $e->getMessage() . "\n";
}

echo "\n=== Columns in cdr_ctdt ===\n";
try {
    $stmt = $pdo->query("DESCRIBE cdr_ctdt");
    while ($row = $stmt->fetch()) {
        echo "{$row['Field']} - {$row['Type']}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
