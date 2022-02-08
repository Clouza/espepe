<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

if (isset($_POST['payday'])) {
    // $idpembayaran = $_POST['idpembayaran'];
    $idpetugas = Session::get('idpetugas');
    $nis = $_POST['nis'];
    $tglbayar = $_POST['tglbayar'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    // $idspp = $_POST['idspp'];
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
            <div class="form-group">
                <label for="idpembayaran">Id Pembayaran</label>
                <input type="text" name="idpembayaran" id="idpembayaran" disabled value="" placeholder="Terisi otomatis">
            </div>

            <div class="form-group">
                <label for="nisn">Nis</label>
                <!-- <input type="number" name="nisn" id="nisn"> -->
                <select name="nis" id="nis" required>
                    <option value="" selected disabled>-- Pilih NIS -- </option>
                    <?php foreach ($siswa as $s) : ?>
                        <option value="<?= $s['nisn']; ?>"><?= $s['nis']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="information" id="information"></div>
            </div>

            <div class="form-group">
                <label for="tglbayar">Tanggal Bayar</label>
                <input type="date" name="tglbayar" id="tglbayar" required>
            </div>

            <div class="form-group">
                <label for="bulan">Bulan dibayar</label>
                <!-- <input type="text" name="bulan" id="bulan"> -->
                <select name="bulan" id="bulan" required>
                    <option value="" selected disabled>-- Pilih Bulan --</option>
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
                <input type="text" name="tahun" id="tahun" readonly value="<?= date('Y'); ?>">
            </div>

            <div class="form-group">
                <label for="idspp">Id Spp</label>
                <input type="text" name="idspp" id="idspp" disabled placeholder="Terisi otomatis sesuai NIS">
            </div>

            <div class="form-group">
                <label for="jumlahbayar">Jumlah Bayar</label>
                <input type="number" name="jumlahbayar" id="jumlahbayar" min="0" required>
            </div>

            <button type="submit" class="btn" name="payday">Input data</button>

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