<?php
require_once '../../app.php';

use App\Controllers\Database;
use App\Controllers\Flasher;
use App\Controllers\Session;

// cek token
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // get token in database
    $db = new Database;
    $tokenInDatabase = $db->table('token')->where('token', '=', $token)->get()->fetch_assoc();

    // check token if match
    if (!is_null($tokenInDatabase)) {
        $expiredTime = $tokenInDatabase['time'];
        // check if token expired
        if (time() - $expiredTime < (60 * 60 * 24)) { // sehari
            // change password
            if (isset($_POST['change'])) {
                $email = $tokenInDatabase['email'];
                $password = $_POST['newpassword'];
                $change = $db->changepassword($email, $password); // true

                if ($change) {
                    // delete token in database
                    $db->deleteToken($tokenInDatabase['id_token']);

                    Flasher::set('Password berhasil diubah! Silahkan login.');
                }
            }
        } else {
            Flasher::set('Token kedaluarsa & akan dihapus');

            // delete token in database
            $db->deleteToken($tokenInDatabase['id_token']);
        }
    } else {
        return abort(404);
    }
} else {
    return abort(404);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="../../../assets/css/auth.css">
    <link rel="stylesheet" href="../../../assets/css/global.css">
</head>

<body>
    <form action="?token=<?= $token ?>" method="post">
        <div class="form-group">
            <label for="newpassword">New Password</label>
            <input type="password" name="newpassword" id="newpassword" required tabindex="1">
        </div>
        <button type="submit" name="change">Update</button>
    </form>

    <?php if (Session::has('flashMessage')) : ?>
        <div class="flash-message">
            <span><?= Flasher::get() ?></span>
        </div>
    <?php endif; ?>
</body>

</html>