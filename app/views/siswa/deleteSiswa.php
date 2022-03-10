<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (Session::get('level') != '2') {
    return abort(404);
}

$idsiswa = $_GET['ns'];

Dashboard::deleteSiswa($idsiswa);
Flasher::set('Siswa dihapus!');

redirect('index.php');
