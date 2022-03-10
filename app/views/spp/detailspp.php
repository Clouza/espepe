<?php
require_once '../../app.php';

use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

if (Session::get('level') != '2') {
    return abort(404);
}

if (!$_GET['ns']) {
    return abort(404);
}

$spp = Dashboard::readDetailSPPWithDetailSiswa($_GET['ns']);
$pembayaran = Dashboard::readBulanByPembayaran($spp['id_spp'], date('Y'));
$bulan = Dashboard::$bulan;

$merge = array_merge($pembayaran, $bulan);
$cek = array_count_values($merge);

foreach ($merge as $p) {
    if ($cek[$p] > 1) {
        $dibayar[] = "<span class='text-success'>$p &#10003; </span>";
    } else {
        $dibayar[] = "<span class='text-danger'>$p &#10005; </span>";
    }
}

$dibayar = array_slice($dibayar, count($pembayaran));
$years = (int)date('Y');

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Detail</div>
    <div class="container">
        <a href="index.php"><button class="btn btn-action-back">&#8592; Kembali</button></a>
        <a href="../siswa/updateSiswa.php?ns=<?= $spp['nis']; ?>"><button class="btn">Edit Siswa</button></a>
        <h1><?= $spp['nama']; ?> </h1>
        <small><?= $spp['nisn']; ?> (<?= $spp['nis']; ?>)</small>
        <div class="d-flex flex-start justify-content-between mt-1 flex-wrap">
            <div class="group">
                <div class="group-content">
                    <h4>ID SPP</h4>
                    <span><?= $spp['id_spp']; ?></span>
                </div>
                <div class="group-content">
                    <h4>Total pembayaran</h4>
                    <span><?= count($pembayaran); ?> kali</span>
                </div>
                <div class="group-content">
                    <h4>Total yang harus dibayar (<?= $spp['tahun']; ?>)</h4>
                    <span>Rp<?= number_format($spp['nominal'], '2', ',', '.'); ?>-</span>
                </div>
                <div class="group-content" id="bulanDiBayar" data-spp="<?= $spp['id_spp']; ?>">
                    <div class="d-flex">
                        <h4>Bulan yang sudah dibayar</h4>
                        <select name="years" id="years">
                            <?php for ($i = $years; $i > $years - 3; $i--) : ?>
                                <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="group-content" id="cekbayar">
                    <?php foreach ($dibayar as $d) : ?>
                        <span><?= $d; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="group">
                <div class="group-content">
                    <h4>Status Siswa</h4>
                    <?php if ($spp['is_deleted'] == 0) : ?>
                        <span>Aktif</span>
                    <?php else : ?>
                        <span>Tidak aktif</span>
                    <?php endif; ?>
                </div>
                <div class="group-content">
                    <h4>Kelas</h4>
                    <span><?= $spp['nama_kelas']; ?></span>
                </div>
                <div class="group-content">
                    <h4>No Telp</h4>
                    <span><?= $spp['no_telp']; ?></span>
                </div>
                <div class="group-content">
                    <h4>Email</h4>
                    <span><?= $spp['email']; ?></span>
                </div>
                <div class="group-content">
                    <h4>Alamat</h4>
                    <span><?= $spp['alamat']; ?></span>
                </div>
            </div>
        </div>

    </div>
</section>

<?php
reqFile('../templates/footer.php');
?>