<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (Session::get('level') != '2') {
    return abort(404);
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];
    $level = Session::get('level');
    $username = Session::get('authenticated');
    $reset = Dashboard::reset($password, $level, $username);

    if ($reset) {
        Flasher::set('Reset Berhasil!');
    } else {
        Flasher::set('Reset Gagal!');
    };
} else {
    Flasher::set('Forbidden!');
}

redirect('index.php');
