<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

if (Session::get('level') != '2') {
    return abort(404);
}

if (isset($_GET['q'])) {
    $q = $_GET['q'];
    $siswa = Dashboard::searchSiswa($q);
} else if (isset($_GET['qk'])) {
    $k = $_GET['qk'];
    $kelas = Dashboard::searchKelas($k);
} else {
    return abort(404);
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Mencari.. <?= (isset($_GET['q'])) ? $q : ((isset($_GET['qk'])) ? str_replace('-', ' ', strtoupper($k)) : '') ?></div>
    <div class="container">
        <?php if (isset($siswa)) : ?>
            <?php foreach ($siswa as $s) : ?>
                <div class="search-card">
                    <h2><?= $s['nama']; ?></h2>
                    <small><?= $s['nama_kelas']; ?></small>
                    <small><?= $s['nis']; ?></small>
                    <small><?= $s['email']; ?></small>
                    <a href="../spp/detailspp.php?ns=<?= $s['nis']; ?>">Lihat siswa...</a>
                </div>
            <?php endforeach; ?>
        <?php elseif (!is_null($kelas)) : ?>
            <?php foreach ($kelas as $k) : ?>
                <div class="search-card">
                    <h2><?= $k['nama']; ?></h2>
                    <small><?= $k['nama_kelas']; ?></small>
                    <a href="../spp/detailspp.php?ns=<?= $k['nis']; ?>">Lihat siswa...</a>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <h2>Tidak ditemukan</h2>
        <?php endif; ?>
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