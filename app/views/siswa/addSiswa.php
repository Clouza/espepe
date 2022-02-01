<?php
require_once '../../app.php';

use App\Controllers\Session;
use App\Controllers\Dashboard;
use App\Controllers\Flasher;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$otorisasi = Dashboard::getRole();

if (isset($_POST['tambahSiswa'])) {
    // $namakelas = $_POST['namakelas'];
    // $kompetensikeahlian = $_POST['kompetensikeahlian'];
    // Dashboard::addKelas($namakelas, $kompetensikeahlian);
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
        <a href="index.php">Kembali</a>
        <form action="" method="post" class="form-dashboard">
            <div class="form-group">
                <label for="nisn">NISN</label>
                <input type="text" name="nisn" id="nisn" required>
            </div>
            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" name="nis" id="nis" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" required>
            </div>
            <div class="form-group">
                <label for="idkelas">ID Kelas</label>
                <select name="idkelas" id="idkelas">
                    <option value="" selected disabled>-- Pilih Kelas --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" required>
            </div>
            <div class="form-group">
                <label for="notelp">No Telp</label>
                <input type="text" name="notelp" id="notelp" required>
            </div>
            <div class="form-group">
                <label for="idspp">ID spp</label>
                <input type="text" name="idspp" id="idspp" required value="Terisi otomatis" disabled>
            </div>
            <button type="submit" name="tambahSiswa">Tambah</button>
        </form>
    </div>
</section>

<?php
reqFile('../templates/footer.php');
?>