<?php
// معلومات الاستضافة (تجدها في لوحة تحكم الـ Host)
$host = "db.fr-pari1.bengt.wasmernet.com";
$user = "c73925617c19800010c63ec63516"; 
$pass = "069ec739-2561-7d3f-8000-57290459ab6c";
$db   = "database_app";

$conn = new mysqli($host, $user, $pass, $db);

// للتأكد من دعم اللغة العربية بشكل صحيح
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>
