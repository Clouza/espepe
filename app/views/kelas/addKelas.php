<?php
require_once '../../app.php';

use App\Controllers\Session;
use App\Controllers\Dashboard;
use App\Controllers\Flasher;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

if (Session::get('level') != '2') {
    return abort(404);
}

$otorisasi = Dashboard::getRole();

if (isset($_POST['tambahKelas'])) {
    $namakelas = $_POST['namakelas'];
    $kompetensikeahlian = $_POST['kompetensikeahlian'];
    $kelas = $_POST['kelas'];
    $harga = $_POST['harga'];
    Dashboard::addKelas($namakelas, $kompetensikeahlian, $kelas, $harga);
    Flasher::set('Kelas ditambahkan!');
    redirect('index.php');
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Tambah Kelas</div>
    <div class="container">
        <a href="index.php"><button class="btn btn-action-back">&#8592; Kembali</button></a>
        <form action="" method="post" class="form-dashboard">
            <div class="form-group">
                <label for="namaKelas">Nama Kelas</label>
                <input type="text" name="namakelas" id="namaKelas" placeholder="XII RPL 2" required>
            </div>
            <div class="form-group">
                <label for="kompetensiKeahlian">Kompetensi Keahlian</label>
                <input type="text" name="kompetensikeahlian" id="kompetensiKeahlian" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas (Angka)</label>
                <input type="text" name="kelas" id="kelas" value="" placeholder="Terisi otomatis" readonly>
            </div>
            <div class="form-group">
                <label for="harga">Harga yang ditetapkan (Rupiah)</label>
                <input type="number" name="harga" id="harga" required>
            </div>
            <button type="submit" class="btn btn-confirm" name="tambahKelas">Tambah</button>
        </form>
    </div>
</section>

<?php
reqFile('../templates/footer.php');
?>