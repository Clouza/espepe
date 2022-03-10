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

$kelas = Dashboard::readKelasById($_GET['idkelas']);

if (isset($_POST['updateKelas'])) {
    $idkelas = $_POST['idkelas'];
    $nama = $_POST['namakelas'];
    $kompetensi = $_POST['kompetensi'];
    $kelas = $_POST['kelas'];
    $harga = $_POST['harga'];
    Dashboard::updateKelas($idkelas, $nama, $kompetensi, $kelas, $harga);
    Flasher::set('Kelas berhasil diubah!');
    redirect('index.php');
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Update Kelas</div>
    <div class="container">
        <a href="index.php"><button class="btn btn-action-back">&#8592; Kembali</button></a>
        <form action="" method="post" class="form-dashboard">
            <input type="hidden" name="idkelas" value="<?= $kelas['id_kelas']; ?>">
            <div class="form-group">
                <label for="namaKelas">Nama Kelas</label>
                <input type="text" name="namakelas" id="namaKelas" value="<?= $kelas['nama_kelas'] ?>" required>
            </div>
            <div class="form-group">
                <label for="kompetensi">Kompetensi Keahlian</label>
                <input type="text" name="kompetensi" id="kompetensi" value="<?= $kelas['kompetensi_keahlian'] ?>" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas (Angka)</label>
                <input type="text" name="kelas" id="kelas" value="<?= $kelas['kelas'] ?>" readonly placeholder="Terisi Otomatis">
            </div>
            <div class="form-group">
                <label for="harga">Harga yang ditetapkan (Rupiah)</label>
                <input type="text" name="harga" id="harga" value="<?= $kelas['harga'] ?>" required>
            </div>
            <button type="submit" class="btn btn-confirm" name="updateKelas">Update</button>
        </form>
    </div>
</section>

<?php
reqFile('../templates/footer.php');
?>