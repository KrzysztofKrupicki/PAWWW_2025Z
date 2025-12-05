<?php
/**
 * Plik konfiguracyjny
 * Zawiera dane do połączenia z bazą danych oraz dane logowania do panelu admina.
 */

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'moja_strona';

// Połączenie z bazą danych
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
if (!$link) {
    die("<b>Przerwano połączenie z bazą danych!</b>");
}

// Dane logowania do panelu administratora
$login = 'admin';
$pass = 'zaq1@WSX';

// Konfiguracja SMTP dla formularza kontaktowego
$smtp_host = 'smtp.gmail.com';
$smtp_auth = true;
$smtp_username = 'news600613@gmail.com';
$smtp_password = 'zulneplolmamifbq';
$smtp_port = 587;
?>