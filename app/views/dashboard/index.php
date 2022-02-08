<?php
require_once '../../app.php';

use App\Controllers\Session;

if (!Session::has('authenticated')) {
  return redirect('../../index.php');
}

if (Session::get('nisn')) {
  return redirect('history.php');
} else {
  return redirect('pembayaran.php');
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
  <div class="text">Hai, <?= Session::get('profile'); ?></div>
</section>

<div class="flash-message">
  <span>Dashboard Moment</span>
</div>

<?php
reqFile('../templates/footer.php');
?>