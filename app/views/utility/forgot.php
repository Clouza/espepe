<?php

require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\MailController;

if (isset($_POST['sendtoken'])) {
    $email = $_POST['email'];
    $verify = new MailController($email);

    if ($verify) { // true
        Flasher::set('Token berhasil dikirim. Cek email Anda!');
    } else {
        Flasher::set('Token gagal dikirim! Email tidak ada.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="../../../assets/css/auth.css">
    <link rel="stylesheet" href="../../../assets/css/global.css">
</head>

<body>
    <form action="" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required tabindex="1">
        </div>
        <button type="submit" name="sendtoken">Send Token</button>
    </form>

    <?php if (Session::has('flashMessage')) : ?>
        <div class="flash-message">
            <span><?= Flasher::get() ?></span>
        </div>
    <?php endif; ?>
</body>

</html>