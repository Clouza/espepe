<?php
require_once '../../app.php';

use App\Controllers\Session;
use App\Controllers\Dashboard;
use App\Controllers\Flasher;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}
$kelas = Dashboard::readKelasById($_GET['idkelas']);

if (isset($_POST['updateKelas'])) {
    $idkelas = $_POST['idkelas'];
    $nama = $_POST['namakelas'];
    $kompetensi = $_POST['kompetensi'];
    Dashboard::updateKelas($idkelas, $nama, $kompetensi);
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
        <a href="index.php">Kembali</a>
        <form action="" method="post" class="form-dashboard">
            <input type="hidden" name="idkelas" value="<?= $kelas['id_kelas']; ?>">
            <div class="form-group">
                <label for="namakelas">Nama Kelas</label>
                <input type="text" name="namakelas" id="namakelas" value="<?= $kelas['nama_kelas'] ?>" required>
            </div>
            <div class="form-group">
                <label for="kompetensi">Kompetensi Keahlian</label>
                <input type="text" name="kompetensi" id="kompetensi" value="<?= $kelas['kompetensi_keahlian'] ?>" required>
            </div>
            <button type="submit" class="btn" name="updateKelas">Update</button>
        </form>
    </div>
</section>

<?php
reqFile('../templates/footer.php');
?>