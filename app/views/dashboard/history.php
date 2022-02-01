<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$history = Dashboard::getHistory(Session::get('nisn'));

if (isset($_POST['cariNIS'])) {
    $nis = $_POST['nis'];
    $siswa = Dashboard::findSiswaByNis($nis);
    if (!is_int($siswa)) {
        Flasher::set('Siswa tidak ditemukan!');
    }
    $history = Dashboard::getHistory($siswa);
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">History Pembayaran</div>
    <div class="container">
        <?php if (Session::has('level')) : ?>
            <form action="" method="post">
                <input type="text" placeholder="Cari NIS" name="nis">
                <button type="submit" name="cariNIS">Cari</button>
                <button onclick="window.print()">Cetak</button>
            </form>
        <?php endif; ?>
        <table>
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
                    <td><?= $h['bulan_dibayar']; ?></td>
                    <td><?= $h['tahun_dibayar']; ?></td>
                    <td><?= $h['jumlah_dibayar']; ?></td>
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