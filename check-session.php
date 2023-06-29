<?php
session_start();

// Oturum kontrolü yap
if (isset($_SESSION['username'])) {
    // Oturum geçerli, başarılı yanıt döndür
    echo "success";
} else {
    // Oturum geçerli değil, hata yanıtı döndür
    echo "error";
}
?>
