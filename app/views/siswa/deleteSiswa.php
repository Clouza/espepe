<?php
require_once '../../app.php';

use App\Controllers\Dashboard;
use App\Controllers\Flasher;

$idsiswa = $_GET['idsiswa'];

Dashboard::deleteSiswa($idsiswa);
Flasher::set('Siswa dihapus!');

redirect('index.php');
