<?php
header('Content-Type: application/json');

// Koneksi ke database
$host = "localhost";
$dbname = "kode_download";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Koneksi gagal: " . $e->getMessage(),
        "fileUrl" => ""
    ]);
    exit();
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];

    // Cek apakah kode valid dan belum digunakan
    $stmt = $pdo->prepare("SELECT * FROM generated_codes WHERE code = :code AND is_used = 0");
    $stmt->execute(['code' => $code]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $file_name = $row['file_name'];

        // Tandai kode sebagai digunakan
        $updateStmt = $pdo->prepare("UPDATE generated_codes SET is_used = 1 WHERE code = :code");
        $updateStmt->execute(['code' => $code]);

        // Buat URL file GitHub
        $fileUrl = "https://github.com/username/repository/raw/branch/" . $file_name; // Sesuaikan URL GitHub Anda

        echo json_encode([
            "success" => true,
            "message" => "Kode valid!",
            "fileUrl" => $fileUrl
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Kode tidak valid atau sudah digunakan!",
            "fileUrl" => ""
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Metode request tidak didukung.",
        "fileUrl" => ""
    ]);
}
?>