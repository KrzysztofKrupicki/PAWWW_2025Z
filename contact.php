<?php
/**
 * Plik contact.php
 * Obsługuje formularz kontaktowy oraz resetowanie hasła administratora.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'cfg.php';

echo '<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
    <link rel="stylesheet" href="./css/contact.css">
</head>';

echo PokazKontakt();

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_email'])) {
        if (!empty($_POST['temat']) && !empty($_POST['tresc']) && !empty($_POST['email'])) {
            $odbiorca = $_POST['email'];
            WyslijMailKontakt($odbiorca);
        } else {
            echo '<p class="error">[nie wypełniłeś wszystkich pól]</p>';
            echo PokazKontakt();
        }
    } elseif (isset($_POST['reset_pass'])) {
        PrzypomnijHaslo();
    }
}

/**
 * Wyświetla formularz kontaktowy
 */
function PokazKontakt()
{
    return '
        <div class="send_mail_form">
        <form method="POST" action="">
            <h2 class="form-header">Formularz kontaktowy i resetujący hasło</h2>
            
            <div class="form-group">
                <label for="temat">Temat:</label>
                <input type="text" id="temat" name="temat" class="form-input">
            </div>
            
            <div class="form-group">
                <label for="tresc">Treść wiadomości:</label>
                <textarea id="tresc" name="tresc" rows="5" class="form-textarea"></textarea>
            </div>
            
            <div class="form-group">
                <label for="email">Twój e-mail:</label>
                <input type="email" id="email" name="email" required class="form-input">
            </div>

            <p class="form-info">Aby zresetować hasło, uzupełnij tylko pole z adresem e-mail i kliknij „Resetuj hasło”.</p>
            
            <div class="form-actions">
                <button type="submit" name="send_email" class="btn btn-primary">Wyślij</button>
                <button type="submit" name="reset_pass" class="btn btn-secondary">Resetuj hasło</button>
            </div>
        </form>
        </div>
    ';
}

/**
 * Funkcja przypominająca hasło administratora
 * Wysyła maila na podany adres (powinien to być adres admina, ale tutaj jest dowolny z inputa - uwaga security)
 * W prawdziwej aplikacji należałoby sprawdzić czy email jest w bazie adminów.
 */
function PrzypomnijHaslo()
{
    global $pass, $smtp_host, $smtp_auth, $smtp_username, $smtp_password, $smtp_port;

    if (empty($_POST['email'])) {
        echo '<p class="error">[brak adresu email]</p>';
        echo PokazKontakt();
        return;
    }

    $mail = new PHPMailer(true);

    try {
        // Konfiguracja serwera SMTP
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = $smtp_auth;
        $mail->Username = $smtp_username;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtp_port;


        $mail->setFrom($smtp_username, 'Panel administratora');
        $mail->addAddress($_POST['email']);

        // Treść
        $mail->isHTML(false);
        $mail->Subject = "Przypomnienie hasła do panelu admina";
        $mail->Body = "Twoje hasło administratora to: $pass";

        $mail->send();
        echo '<p class="success">Hasło zostało wysłane.</p>';
    } catch (Exception $e) {
        echo "<p class='error'>Błąd wysyłki: {$mail->ErrorInfo}</p>";
    }
}

/**
 * Funkcja wysyłająca maila kontaktowego
 */
function WyslijMailKontakt($odbiorca)
{
    global $smtp_host, $smtp_auth, $smtp_username, $smtp_password, $smtp_port;

    $mail = new PHPMailer(true);

    try {
        // Konfiguracja serwera SMTP
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = $smtp_auth;
        $mail->Username = $smtp_username;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtp_port;

        // Adresaci
        // UWAGA: setFrom musi być zgodny z kontem SMTP gmaila, inaczej może odrzucić.
        // Ustawiamy Reply-To na maila użytkownika.
        $mail->setFrom($smtp_username, 'Formularz kontaktowy');
        $mail->addReplyTo($_POST['email']);
        $mail->addAddress($odbiorca); // Tutaj mail idzie do tego co wpisał user w polu email? To trochę dziwne, ale tak było w oryginale.

        // Treść
        $mail->isHTML(false);
        $mail->Subject = $_POST['temat'];
        $mail->Body = $_POST['tresc'];

        $mail->send();
        echo '<p class="success">Wiadomość została wysłana.</p>';
    } catch (Exception $e) {
        echo "<p class='error'>Błąd wysyłki: {$mail->ErrorInfo}</p>";
    }
}
?>