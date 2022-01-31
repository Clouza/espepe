<?php
require_once '../../app.php';

use App\Controllers\Session;
use App\Controllers\Dashboard;
use App\Controllers\Flasher;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$otorisasi = Dashboard::getRole();

if (isset($_POST['tambahPetugas'])) {
    $idpetugas = $_POST['idpetugas'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $otorisasi = $_POST['otorisasi'];
    Dashboard::addPetugas($idpetugas, $username, $password, $nama, $otorisasi);
}


reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Tambah Petugas</div>
    <div class="container">
        <a href="index.php">Kembali</a>
        <form action="" method="post" class="form-dashboard">
            <div class="form-group">
                <label for="idpetugas">Id Petugas</label>
                <input type="number" name="idpetugas" id="idpetugas">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="passwordfield" required>
                <button type="button" id="passgen">Generate Password</button>
            </div>
            <div class="form-group">
                <label for="nama">Nama Petugas</label>
                <input type="text" name="nama" id="nama" required>
            </div>
            <div class="form-group">
                <label for="otorisasi">Otorisasi</label>
                <select name="otorisasi" id="otorisasi" required>
                    <option value="" selected disabled>-- Pilih otorisasi --</option>
                    <?php foreach ($otorisasi as $o) : ?>
                        <option value="<?= $o['id_otorisasi'] ?>"><?= $o['nama_otorisasi']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="tambahPetugas">Tambah</button>
        </form>
    </div>
</section>



<?php if (Session::has('flashMessage')) : ?>
    <div class="flash-message">
        <span><?= Flasher::get() ?></span>
    </div>
<?php endif; ?>

<?php
reqFile('../templates/footer.php');
?>