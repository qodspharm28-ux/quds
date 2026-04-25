<?php
header('Content-Type: application/json; charset=utf-8');
include 'db.php';

// الحصول على المنتجات لفئة معينة
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

if (empty($category)) {
    http_response_code(400);
    echo json_encode(['error' => 'اسم الفئة مطلوب']);
    exit();
}

$category = $conn->real_escape_string($category);
$query = "SELECT name FROM products WHERE category = '$category' ORDER BY name ASC";
$result = $conn->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'خطأ في الاستعلام: ' . $conn->error]);
    exit();
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row['name'];
}

echo json_encode(['success' => true, 'products' => $products]);
?>
