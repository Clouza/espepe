<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (Session::get('level') != '2') {
    return abort(404);
}

$idkelas = $_GET['idkelas'];

Dashboard::deleteKelas($idkelas);
Flasher::set('Kelas dihapus!');

redirect('index.php');
