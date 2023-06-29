<?php
session_start();

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

// Formdan gönderilen kullanıcı adı ve şifreyi al
$username = $_POST['username'];
$password = $_POST['password'];

// Veritabanında kullanıcıyı sorgula
$sql = "SELECT * FROM kullanıcılar WHERE username = '$username' AND sifre = '$password'";
$result = $connection->query($sql);

if ($result->num_rows == 1) {
    // Kullanıcı doğrulandı, oturum başlat
    $_SESSION['username'] = $username;
    header("Location: talepler.html"); // Kullanıcıyı yönlendirilecek sayfa
} else {
    // Kullanıcı doğrulanmadı, hata mesajı göster
    echo "Geçersiz kullanıcı adı veya şifre.";
}

// Veritabanı bağlantısını kapatma
$connection->close();
?>
