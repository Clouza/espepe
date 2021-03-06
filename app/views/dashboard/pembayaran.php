<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

if (!Session::has('level')) {
    return abort(404);
}

// masukin ke database
if (isset($_POST['payday'])) {
    $idpetugas = Session::get('idpetugas');
    $nis = $_POST['nis'];
    $tglbayar = $_POST['tglbayar'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $jumlah = $_POST['jumlahbayar'];
    Dashboard::payday('', $idpetugas, $nis, $tglbayar, $bulan, $tahun, '', $jumlah);
}

$siswa = Dashboard::getSiswa();

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Pembayaran</div>
    <div class="container">
        <form action="" method="post" class="form-dashboard">
            <input type="hidden" id="petugas" value="<?= Session::get('profile'); ?>">
            <div class="form-group">
                <label for="nisn">Nis</label>
                <select name="nis" id="nis" required>
                    <option value="" selected disabled>-- Pilih NIS -- </option>
                    <?php foreach ($siswa as $s) : ?>
                        <option value="<?= $s['nisn']; ?>"><?= $s['nis']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="information" id="information"></div>
            </div>

            <div class="form-group">
                <label for="tglbayar">Tanggal Bayar (Hari ini)</label>
                <input type="date" name="tglbayar" id="tglbayar" value="" required>
            </div>

            <div class="form-group">
                <label for="bulan">Bulan dibayar</label>
                <select name="bulan" id="currentBulan" required>
                    <option value="januari">Januari</option>
                    <option value="februari">Februari</option>
                    <option value="maret">Maret</option>
                    <option value="april">April</option>
                    <option value="mei">Mei</option>
                    <option value="juni">Juni</option>
                    <option value="juli">Juli</option>
                    <option value="agustus">Agustus</option>
                    <option value="september">September</option>
                    <option value="oktober">Oktober</option>
                    <option value="november">November</option>
                    <option value="desember">Desember</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tahun">Tahun dibayar</label>
                <select name="tahun" id="currentYear">
                    <?php for ($i = (int)date('Y'); $i > (int)date('Y') - 3; $i--) : ?>
                        <option value="<?= $i; ?>"><?= $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlahbayar">Jumlah Bayar (Rupiah)</label>
                <input type="number" name="jumlahbayar" id="jumlahbayar" min="0" placeholder="Terisi otomatis sesuai kelas" readonly required>
            </div>

            <button type="submit" class="btn btn-confirm" name="payday" id="currentPayment">Input data</button>

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