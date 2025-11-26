<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

echo '<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="./css/contact.css">
</head>';

echo PokazKontakt();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_email'])) {
        if (!empty($_POST['temat']) && !empty($_POST['tresc']) && !empty($_POST['email'])) {
            $odbiorca = $_POST['email'];
            WyslijMailKontakt($odbiorca);
        } else {
            echo '[nie wypełniłeś pola]';
            echo PokazKontakt();
        }
    } elseif (isset($_POST['reset_pass'])) {
        PrzypomnijHaslo();
    }
}


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


function PrzypomnijHaslo()
{
    if (empty($_POST['email'])) {
        echo '[brak adresu email]';
        echo PokazKontakt();
        return;
    }

    $admin_pass = "zaq1@WSXcde3";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'news600613@gmail.com';
        $mail->Password = 'zulneplolmamifbq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@najwieksze-mosty-swiata.pl', 'Panel administratora');
        $mail->addAddress($_POST['email']);
        $mail->Subject = "Przypomnienie hasła do panelu admina";
        $mail->Body = "Twoje hasło administratora to: $admin_pass";
        $mail->send();
        echo '[haslo_wyslane]';
    } catch (Exception $e) {
        echo "Błąd wysyłki: {$mail->ErrorInfo}";
    }
}



function WyslijMailKontakt($odbiorca)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'news600613@gmail.com';
        $mail->Password = 'zulneplolmamifbq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($_POST['email'], 'Formularz kontaktowy');
        $mail->addAddress($odbiorca);
        $mail->Subject = $_POST['temat'];
        $mail->Body = $_POST['tresc'];
        $mail->send();
        echo '[wiadomosc_wyslana]';
    } catch (Exception $e) {
        echo "Błąd wysyłki: {$mail->ErrorInfo}";
    }
}


?>