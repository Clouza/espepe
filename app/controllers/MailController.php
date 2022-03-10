<?php

namespace App\Controllers;

use App\Controllers\Database;

class MailController
{
    private
        $from = '',
        $to = '',
        $subject = '',
        $headers = [],
        $message = '';

    public function __construct($email)
    {
        // check email exists
        $db = new Database;
        $check = $db->table('siswa')->where('email', '=', $email)->get();

        if ($check->num_rows > 0) {
            $this->prepare($email);
            return mail($this->to, $this->subject, $this->message, implode("\r\n", $this->headers));
        } else {
            return false;
        }
    }

    private function prepare($email)
    {
        $this->from = 'espepe@zaaportals.com';
        $this->to = $email;
        $this->subject = 'Email Verification';
        $this->headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=iso-8859-1',

            "To: $email",
            'From: espepe@zaaportals.com',
            'X-Mailer: PHP/' . phpversion()
        ];

        $this->message = $this->html($email);

        return $this;
    }

    private function html($email)
    {
        // make random
        $token = str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        // store token to database
        $this->addToken($token, $email);

        return "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta http-equiv='X-UA-Compatible' content='IE=edge'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Email Verification</title></head><body><h1>Berikut adalah link untuk reset password Anda:</h1><a href='http://localhost/espepe/app/views/utility/mailrequest.php?token=$token'>Reset Password</a></body></html>";
    }

    private function addToken($token, $email)
    {
        $db = new Database;
        $db->table('token')->addToken($token, $email);
    }
}
