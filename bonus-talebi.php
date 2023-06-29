<?php
// Veritabanı bağlantısı yapılmalıdır
require_once('dbconfig.php');

// Veritabanı bağlantısı için gerekli bilgiler
$dbHost = $dbConfig['host'];
$dbUsername = $dbConfig['username'];
$dbPassword = $dbConfig['password'];
$dbName = $dbConfig['dbname'];

// Veritabanına bağlanma
$connection = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Bağlantı hatası kontrolü
if ($connection->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $connection->connect_error);
}

// POST verilerini al
$username = $_POST['username'];
$bonus = $_POST['bonus'];

// Diğer verileri hazırla
$status = "Beklemede";
$timestamp = date('Y-m-d H:i:s');

// Kullanıcı adının daha önce eklenip eklenmediğini ve durumun "Beklemede" veya "İşlemde" olup olmadığını kontrol et
$checkQuery = "SELECT COUNT(*) as count FROM talepler WHERE kullanici_adi = ? AND durum IN ('Beklemede', 'İşlemde')";
$checkStatement = $connection->prepare($checkQuery);
$checkStatement->bind_param('s', $username);
$checkStatement->execute();
$checkStatement->bind_result($count);
$checkStatement->fetch();
$checkStatement->close();

if ($count > 0) {
    echo "Hata: Bu kullanıcı zaten bir bonus talebinde bulunmuş.";
} else {
    // Veritabanına veri ekleme
    $insertQuery = "INSERT INTO talepler (kullanici_adi, bonus_id, durum, ekleme_tarihi) VALUES (?, ?, ?, ?)";
    $insertStatement = $connection->prepare($insertQuery);
    $insertStatement->bind_param('siss', $username, $bonus, $status, $timestamp);
    
    if ($insertStatement->execute()) {
        echo "Bonus talebi başarıyla kaydedildi.";
    } else {
        echo "Hata: " . $insertStatement->error;
    }
    
    $insertStatement->close();
}

// Veritabanı bağlantısını kapatma
$connection->close();
?>
    