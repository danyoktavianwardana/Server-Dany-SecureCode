<?php
// Koneksi ke database
$host = "localhost";
$dbname = "kode_download";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Fungsi untuk generate kode acak
function generateCode($length = 10) {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file_name = $_POST['file_name'];
    $code = generateCode(8); // Kode acak dengan panjang 8 karakter

    // Simpan kode dan nama file ke database
    $stmt = $pdo->prepare("INSERT INTO generated_codes (code, file_name) VALUES (:code, :file_name)");
    $stmt->execute(['code' => $code, 'file_name' => $file_name]);

    echo "<div class='container'>";
    echo "<h1>Kode yang dihasilkan:</h1>";
    echo "<p>$code</p>";
    echo "<p>File terkait: $file_name</p>";
    echo "<a href='\php\assets\index.html'>Kembali</a>";
    echo "</div>";
}
?>