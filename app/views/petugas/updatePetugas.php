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
$petugas = Dashboard::readPetugasById($_GET['idpetugas']);

if (isset($_POST['updatePetugas'])) {
    $idpetugas = $_POST['idpetugas'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $otorisasi = $_POST['otorisasi'];
    Dashboard::updatePetugas($idpetugas, $username, $nama, $otorisasi);
    redirect('index.php');
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Update Petugas</div>
    <div class="container">
        <a href="index.php"><button class="btn btn-action-back">&#8592; Kembali</button></a>
        <form action="" method="post" class="form-dashboard">
            <div class="form-group">
                <label for="idpetugas">Id Petugas</label>
                <input type="number" name="idpetugas" id="idpetugas" readonly value="<?= $petugas['id_petugas'] ?>">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= $petugas['username'] ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama Petugas</label>
                <input type="text" name="nama" id="nama" value="<?= $petugas['nama_petugas'] ?>" required>
            </div>
            <div class="form-group">
                <label for="otorisasi">Otorisasi</label>
                <select name="otorisasi" required>
                    <option value="" disabled>-- Pilih otorisasi --</option>
                    <?php foreach ($otorisasi as $o) : ?>
                        <?php if ($o['id_otorisasi'] == $petugas['level']) : ?>
                            <option value="<?= $o['id_otorisasi'] ?>" selected><?= $o['nama_otorisasi']; ?></option>
                        <?php else : ?>
                            <option value="<?= $o['id_otorisasi'] ?>"><?= $o['nama_otorisasi']; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-confirm" name="updatePetugas">Update</button>
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