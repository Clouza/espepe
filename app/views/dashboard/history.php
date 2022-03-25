<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$history = Dashboard::getHistory(Session::get('nisn'));

if (isset($_GET['nis'])) {
    $nis = $_GET['nis'];
    $siswa = Dashboard::findSiswaByNis($nis);
    if (is_null($siswa)) {
        Flasher::set('Siswa tidak ditemukan!');
    } else {
        $history = Dashboard::getHistory((int)$siswa['nisn']);
    }
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">History Pembayaran</div>
    <div class="container">
        <?php if (Session::has('level')) : ?>
            <form action="" method="get">
                <div class="form-group">
                    <input type="text" placeholder="Cari NIS" name="nis" value="<?= isset($_GET['nis']) ? $_GET['nis'] : '' ?>">
                    <button type="submit" class="btn btn-confirm">Cari</button>
                    <?php if (Session::get('level') == 2) : ?>
                        <button type="button" class="btn" id="cetakNota">Cetak</button>
                    <?php endif; ?>
                </div>
            </form>
        <?php endif; ?>
        <?php if (isset($siswa)) : ?>
            <div class="d-flex flex-column">
                <span id="namaSiswa">Nama: <?= $siswa['nama'] . ' (' . $siswa['nis'] . ')' ?></span>
                <span id="kelasSiswa">Kelas: <?= $siswa['nama_kelas']; ?></span>
                <span style="display: none; margin: 0; padding: 0;" id="nisSiswa"><?= $siswa['nis']; ?></span>
            </div>
        <?php endif; ?>
        <table id="history" class="mt-1">
            <tr>
                <th>Petugas</th>
                <th>Tanggal</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Jumlah</th>
            </tr>
            <?php foreach ($history as $h) : ?>
                <tr>
                    <td><?= $h['username']; ?></td>
                    <td><?= $h['tgl_bayar']; ?></td>
                    <td><?= ucwords($h['bulan_dibayar']); ?></td>
                    <td><?= $h['tahun_dibayar']; ?></td>
                    <td>Rp<?= number_format($h['jumlah_dibayar'], '2', '.', ','); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
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