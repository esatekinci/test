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

// Bonus taleplerini getir
$sql = "SELECT talepler.kullanici_adi, bonuslar.bonus_adi, talepler.durum, talepler.ekleme_tarihi, kullanıcılar.username, talepler.talep_id FROM talepler LEFT JOIN bonuslar ON talepler.bonus_id = bonuslar.bonus_id LEFT JOIN kullanıcılar ON talepler.kullanici = kullanıcılar.id;";
$result = $connection->query($sql);

// Talepleri JSON olarak döndür
$talepler = array();
while ($row = $result->fetch_assoc()) {
    $talepler[] = $row;
}
echo json_encode($talepler);

// Veritabanı bağlantısını kapatma
$connection->close();
?>
