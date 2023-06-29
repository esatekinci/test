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

// Bonusları getir
$sql = "SELECT * FROM bonuslar";
$result = $connection->query($sql);

// Bonusları JSON olarak döndür
$bonuslar = array();
while ($row = $result->fetch_assoc()) {
    $bonuslar[] = $row;
}
echo json_encode($bonuslar);

// Veritabanı bağlantısını kapatma
$connection->close();
?>
