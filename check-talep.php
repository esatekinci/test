<?php
require_once('dbconfig.php');

$dbHost = $dbConfig['host'];
$dbUsername = $dbConfig['username'];
$dbPassword = $dbConfig['password'];
$dbName = $dbConfig['dbname'];

$connection = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($connection->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['pending-username'];

    $query = "SELECT sira, durum, bonus_adi
              FROM (
                SELECT talep_id, kullanici_adi, durum, bonus_adi, @rownum := @rownum + 1 AS sira
                FROM talepler, bonuslar, (SELECT @rownum := 0) r
                WHERE talepler.bonus_id = bonuslar.bonus_id
                AND durum IN ('Beklemede', 'İşlemde')
                ORDER BY talep_id ASC
              ) AS t
              WHERE kullanici_adi = ?";
              
    $statement = $connection->prepare($query);
    $statement->bind_param('s', $username);
    $statement->execute();
    $statement->bind_result($sira, $durum, $bonus_adi);

    echo '<table class="bekleyenbonus">
            <tr>
                <th>Sıra</th>
                <th>Bonus Adı</th>
                <th>Durum</th>
            </tr>';

    while ($statement->fetch()) {
        echo '<tr>
                <td>' . $sira . '</td>
                <td>' . $bonus_adi . '</td>
                <td>' . $durum . '</td>
              </tr>';
    }

    echo '</table>';

    $statement->close();
    $connection->close();
}

?>
