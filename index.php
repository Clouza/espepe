<?php
require_once './app/app.php';

use App\Controllers\AuthenticationController as Auth;
use App\Controllers\Session;
use App\Controllers\Flasher as Flash;

if (Session::has('authenticated')) {
    return redirect('app/views/dashboard.php');
}

// check email & password
Auth::checkLog($_POST);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="./assets/css/auth.css">
    <link rel="stylesheet" href="./assets/css/global.css">
</head>

<body>
    <form action="" method="post">
        <div class="form-group">
            <h1>Hi, Welcome back!</h1>
        </div>
        <div class="form-group">
            <label for="user">Nomor Induk Siswa (NIS)</label>
            <input type="text" id="user" name="user" required tabindex="1">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required tabindex="2">
            <small><a href="./app/views/utility/forgot.php">Lupa Password?</a></small>
        </div>
        <button type="submit">Sign in</button>
    </form>

    <?php if (Session::has('flashMessage')) : ?>
        <div class="flash-message">
            <span><?= Flash::get() ?></span>
        </div>
    <?php endif; ?>
</body>

</html>