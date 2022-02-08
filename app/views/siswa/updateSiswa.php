<?php
require_once '../../app.php';

use App\Controllers\Session;
use App\Controllers\Dashboard;
use App\Controllers\Flasher;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}
$siswa = Dashboard::readSiswaByNis($_GET['idsiswa']);
$kelas = Dashboard::readKelas();

if (isset($_POST['updateSiswa'])) {
    $nisn = $_POST['nisn'];
    $nis = $_POST['nis'];
    $email = $_POST['email'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $notelp = $_POST['notelp'];
    Dashboard::updateSiswa($nisn, $nis, $email, $nama, $kelas, $alamat, $notelp);
    Flasher::set('Siswa berhasil diubah!');
    redirect('index.php');
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Update Siswa</div>
    <div class="container">
        <a href="index.php">Kembali</a>
        <form action="" method="post" class="form-dashboard">
            <input type="hidden" name="idsiswa" value="<?= $siswa['nisn']; ?>">
            <div class="form-group">
                <label for="nisn">NISN</label>
                <input type="text" name="nisn" id="nisn" value="<?= $siswa['nisn'] ?>" required>
            </div>
            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" name="nis" id="nis" value="<?= $siswa['nis'] ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= $siswa['email'] ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="<?= $siswa['nama'] ?>" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <select name="kelas" id="kelas">
                    <option value="" disabled selected>-- Pilih Kelas --</option>
                    <?php foreach ($kelas as $k) : ?>
                        <?php if ($k['id_kelas'] == $siswa['id_kelas']) : ?>
                            <option value="<?= $k['id_kelas'] ?>" selected><?= $k['nama_kelas'] ?></option>
                        <?php else : ?>
                            <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" value="<?= $siswa['alamat'] ?>" required>
            </div>
            <div class="form-group">
                <label for="notelp">No Telp</label>
                <input type="number" min="0" name="notelp" id="notelp" value="<?= $siswa['no_telp'] ?>" required>
            </div>
            <div class="form-group">
                <label for="idspp">Nominal (Rp)</label>
                <input type="text" id="idspp" disabled value="<?= $siswa['nominal']; ?> (Tidak bisa diubah)" required>
            </div>
            <button type="submit" class="btn" name="updateSiswa">Update</button>
        </form>
    </div>
</section>

<?php
reqFile('../templates/footer.php');
?>