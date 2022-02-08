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
        return $this->prepare($email)->send();
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

    private function send()
    {
        return mail($this->to, $this->subject, $this->message, implode("\r\n", $this->headers));
    }

    private function html($email)
    {
        // make random
        $token = base64_encode(random_bytes(64));
        $this->addToken($token, $email);
        return "
            <!DOCTYPE html>
            <html lang='en'>
            
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Email Verification</title>
            </head>
            
            <body>
                <h1>Hai!! Ini link untuk verifikasi email kamu:</h1>
                <a href='http://localhost/espepe/app/views/utility/mailrequest.php?token=$token'>Verifikasi</a>
            </body>
            
            </html>
        ";
    }

    private function addToken($token, $email)
    {
        $db = new Database;
        $db->table('token')->addToken($token, $email);
    }
}
