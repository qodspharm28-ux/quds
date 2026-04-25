<?php
header('Content-Type: application/json; charset=utf-8');
include 'db.php';

// الحصول على كل المنتجات من قاعدة البيانات
$query = "SELECT name, quantity FROM products ORDER BY name ASC";
$result = $conn->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'خطأ في الاستعلام: ' . $conn->error]);
    exit();
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'name' => $row['name'],
        'quantity' => (int)$row['quantity']
    ];
}

echo json_encode(['success' => true, 'products' => $products]);
?>
