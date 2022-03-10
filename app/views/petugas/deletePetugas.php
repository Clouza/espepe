<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (Session::get('level') != '2') {
    return abort(404);
}
$idpetugas = $_GET['idpetugas'];

Dashboard::deletePetugas($idpetugas);
Flasher::set('Petugas dihapus!');

redirect('index.php');
