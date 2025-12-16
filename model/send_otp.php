<?php
require '../vendor/autoload.php'; // đường dẫn đúng tới autoload.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'leminhnhat1326@gmail.com';
        $mail->Password = 'zbweedadacvluvqz';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('ShopLi@gmail.com', 'Website của bạn');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP đăng ký';
        $mail->Body = "Mã OTP của bạn là: <b>$otp</b> (hiệu lực 5 phút)";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
