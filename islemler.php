<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "talep";

// Kullanıcı adını alma
function getUsername($conn)
{
    $username = $_SESSION['username'];
    $sql = "SELECT id, username FROM kullanıcılar WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userid = $row['id'];
        return $row['username'];
    } else {
        return "";
    }
}

// Talepleri getirme
function getTalepler($conn)
{
    $sql = "SELECT talep_id, kullanıcılar.username, bonuslar.bonus_adi, talepler.durum, talepler.ekleme_tarihi, talepler.kullanici 
            FROM talepler 
            LEFT JOIN kullanıcılar ON talepler.kullanici = kullanıcılar.id 
            LEFT JOIN bonuslar ON talepler.bonus_id = bonuslar.bonus_id";
    $result = $conn->query($sql);

    $talepler = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $talepler[] = $row;
        }
    }

    return $talepler;
}

// Talebi güncelleme
function updateTalep($conn, $talepId, $durum, $userid)
{
    $durumText = "";
    if ($durum == 1) {
        $durumText = "İşlemde";
    } elseif ($durum == 2) {
        $durumText = "Onaylandı";
    } elseif ($durum == 3) {
        $durumText = "İptal";
    }

    $sql = "UPDATE talepler SET durum = '$durumText', kullanici = '$userid' WHERE talep_id = '$talepId'";
    if ($conn->query($sql) === TRUE) {
        return $durumText;
    } else {
        return "error";
    }
}


// Veritabanı bağlantısını oluşturma
$conn = new mysqli($servername, $username, $password, $dbname);

// Veritabanı bağlantısını kontrol etme
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// İşlemi belirleme ve ilgili fonksiyonu çağırma
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if ($_GET["action"] == "getUsername") {
        $response = getUsername($conn);
        echo $response;
    }
    if ($_GET["action"] == "getTalepler") {
        $response = getTalepler($conn);
        echo json_encode($response);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "updateTalep") {
        $talepId = $_POST["talepId"];
        $durum = $_POST["durum"];
        $username = isset($_POST["username"]) ? $_POST["username"] : null;
        $userid = "";

        $sql = "SELECT id, username FROM kullanıcılar WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userid = $row['id'];
        } else {
            echo "";
            exit;
        }
        $response = updateTalep($conn, $talepId, $durum, $userid);
        echo $response;
    }
}


// Veritabanı bağlantısını kapatma
$conn->close();
?>
