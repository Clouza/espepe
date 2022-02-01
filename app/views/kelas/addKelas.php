<?php
require_once '../../app.php';

use App\Controllers\Session;
use App\Controllers\Dashboard;
use App\Controllers\Flasher;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$otorisasi = Dashboard::getRole();

if (isset($_POST['tambahKelas'])) {
    $namakelas = $_POST['namakelas'];
    $kompetensikeahlian = $_POST['kompetensikeahlian'];
    Dashboard::addKelas($namakelas, $kompetensikeahlian);
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
        <a href="index.php">Kembali</a>
        <form action="" method="post" class="form-dashboard">
            <div class="form-group">
                <label for="namaKelas">Nama Kelas</label>
                <input type="text" name="namakelas" id="namaKelas" required>
            </div>
            <div class="form-group">
                <label for="kompetensiKeahlian">Kompetensi Keahlian</label>
                <input type="text" name="kompetensikeahlian" id="kompetensiKeahlian" required>
            </div>
            <button type="submit" name="tambahKelas">Tambah</button>
        </form>
    </div>
</section>

<?php
reqFile('../templates/footer.php');
?>