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

$kelas = Dashboard::getKelas();

if (isset($_POST['tambahSiswa'])) {
    $nisn = $_POST['nisn'];
    $nis = $_POST['nis'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $idkelas = $_POST['idkelas'];
    $alamat = $_POST['alamat'];
    $notelp = $_POST['notelp'];

    Dashboard::addSiswa($nisn, $nis, $email, $password, $nama, $idkelas, $alamat, $notelp);
    Flasher::set('Siswa ditambahkan!');
    redirect('index.php');
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Tambah Siswa</div>
    <div class="container">
        <a href="index.php"><button class="btn btn-action-back">&#8592; Kembali</button></a>
        <form action="" method="post" class="form-dashboard">
            <div class="form-group">
                <label for="nisn">NISN</label>
                <input type="number" min="0" name="nisn" id="nisn" required>
            </div>
            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="number" min="" name="nis" id="newnis" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="newpasswordforsiswa" readonly required>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" required>
            </div>
            <div class="form-group">
                <label for="idkelas">Kelas</label>
                <select name="idkelas" id="idkelas">
                    <option value="" selected disabled>-- Pilih Kelas --</option>
                    <?php foreach ($kelas as $k) : ?>
                        <option value="<?= $k['id_kelas']; ?>"><?= $k['nama_kelas']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" required>
            </div>
            <div class="form-group">
                <label for="notelp">No Telp</label>
                <input type="number" min="0" name="notelp" id="notelp" required>
            </div>
            <div class="form-group">
                <label for="idspp">ID spp</label>
                <input type="text" id="idspp" required value="Terisi otomatis" disabled>
            </div>
            <button type="submit" class="btn btn-confirm" name="tambahSiswa">Tambah</button>
        </form>
    </div>
</section>

<?php
reqFile('../templates/footer.php');
?>