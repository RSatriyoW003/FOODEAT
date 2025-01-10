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

// Ambil data dari formulir dengan validasi
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$message = htmlspecialchars(trim($_POST['message']));

// Validasi input
if (empty($name) || empty($email) || empty($message)) {
    die("Semua kolom harus diisi!");
}

// Gunakan Prepared Statement untuk keamanan
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    echo "Pesan berhasil dikirim!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>