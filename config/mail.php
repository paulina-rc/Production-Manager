<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function getMailer()
{
    $env = parse_ini_file(__DIR__ . '/../.env');

    $mail = new PHPMailer(true);

    $mail->isSMTP();

    $mail->Host = $env['MAIL_HOST'];
    $mail->SMTPAuth = true;

    $mail->Username = $env['MAIL_USERNAME'];
    $mail->Password = $env['MAIL_PASSWORD'];

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $env['MAIL_PORT'];

    $mail->CharSet = 'UTF-8';

    $mail->setFrom(
        $env['MAIL_USERNAME'],
        $env['MAIL_FROM_NAME']
    );

    return $mail;
}