<?php
// Koneksi ke MySQL tanpa memilih database dulu
$host = "localhost";
$username = "root"; // Sesuaikan dengan username MySQL Anda
$password = "";     // Sesuaikan dengan password MySQL Anda
$dbname = "kode_download";

try {
    // Koneksi ke MySQL server
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Membuat database jika belum ada
    $createDB = "CREATE DATABASE IF NOT EXISTS $dbname";
    $pdo->exec($createDB);
    echo "Database '$dbname' berhasil dibuat atau sudah ada.<br>";

    // Koneksi ulang ke database yang baru dibuat
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Membuat tabel `generated_codes` jika belum ada
    $createTable = "
    CREATE TABLE IF NOT EXISTS generated_codes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(8) NOT NULL UNIQUE, -- Kode unik yang di-generate
        file_name VARCHAR(255) NOT NULL, -- Nama file yang terkait dengan kode
        is_used TINYINT(1) DEFAULT 0,    -- 0 berarti kode belum digunakan, 1 berarti sudah digunakan
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($createTable);
    echo "Tabel 'generated_codes' berhasil dibuat atau sudah ada.<br>";

} catch (PDOException $e) {
    echo "Koneksi atau pembuatan database/tabel gagal: " . $e->getMessage();
}
?>