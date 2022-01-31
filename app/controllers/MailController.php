<?php

namespace App\Controllers;

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

        $this->message = $this->html();

        return $this;
    }

    private function send()
    {
        // return mail($this->to, $this->subject, $this->message, implode("\r\n", $this->headers));
        return mail('siwananda23@gmail.com', 'Email Verification', 'Just Email');
    }

    private function html()
    {
        // make random
        $random = base64_encode(random_bytes(64));
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
                <h1>Hai <s>pemain 1</s> kamu yang disana! Ini link untuk verifikasi email kamu:</h1>
                <a href='http://localhost/espepe/app/views/utility/mailrequest.php?token=$random'>Verifikasi email ini</a>
            </body>
            
            </html>";
    }
}
