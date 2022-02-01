<?php
require_once '../../app.php';

use App\Controllers\Dashboard;
use App\Controllers\Flasher;

$idkelas = $_GET['idkelas'];

Dashboard::deleteKelas($idkelas);
Flasher::set('Kelas dihapus!');

redirect('index.php');
