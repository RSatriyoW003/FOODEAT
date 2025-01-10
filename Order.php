<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FOODEAT";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari JSON request
$data = json_decode(file_get_contents("php://input"), true);

// Validasi data
if (!isset($data['products']) || !isset($data['total_price']) || $data['total_price'] <= 0) {
    die("Data pesanan tidak valid!");
}

$products = $data['products'];
$total_price = $data['total_price'];

// Simpan setiap produk ke database
foreach ($products as $product) {
    $product_name = htmlspecialchars(trim($product['name']));
    $price = floatval($product['price']);

    // Validasi input
    if (empty($product_name) || $price <= 0) {
        continue; // Lewati produk tidak valid
    }

    // Gunakan Prepared Statement untuk keamanan
    $stmt = $conn->prepare("INSERT INTO orders (product_name, quantity, total_price) VALUES (?, ?, ?)");
    $quantity = 1; // Misalkan quantity selalu 1
    $stmt->bind_param("sid", $product_name, $quantity, $price);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

echo "Checkout berhasil!";
$conn->close();
?>