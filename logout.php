﻿<?php
session_start();

// Oturumu sonlandır
session_destroy();

// Giriş sayfasına yönlendir
header("Location: login.html");
exit;
?>
