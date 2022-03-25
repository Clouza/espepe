// wait until html loaded successfully
window.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector(".sidebar");
  const closeBtn = document.querySelector("#btn");
  const footerSidebar = document.querySelector('#footerSidebar');

  closeBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange();
  });

  // following are the code to change sidebar button(optional)
  function menuBtnChange() {
    if (sidebar.classList.contains("open")) { // if sidebar have class open
      // sidebar.setAttribute('style', 'background-color: transparent');
      closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); // replacing the icons class
    } else {
      // sidebar.setAttribute('style', 'background-color: red');
      closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); // replacing the icons class
    }
  }

  // search siswa
  const search = document.querySelector('#search');
  if (search != null) {
    const searchIcon = search.children[0];
    let searchInput = search.children[1].children[0];

    searchIcon.addEventListener('click', () => {
      sidebar.classList.toggle("open");
      searchInput.focus();
      menuBtnChange();
    });

    searchSuggest(searchInput);

    function searchSuggest(element) {
      let container = element;
      let suggestion = [
        'Cari Siswa...',
        'Cari Nis...',
        'Cari Email...'
      ];


      // looping
      textSequence(0);

      function textSequence(i) {
        if (suggestion.length > i) {
          setTimeout(function () {
            container.setAttribute('placeholder', suggestion[i]);
            textSequence(++i);
          }, 1000);

        } else if (suggestion.length == i) { // loop
          textSequence(0);
        }
      }
    }
  }

  // password genetator
  const passGenButton = document.querySelector('#passgen');
  const passField = document.querySelector('#passwordfield');

  function passwordGenerator(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }

  if (passGenButton != null) {
    passGenButton.addEventListener('click', () => {
      passField.setAttribute('value', passwordGenerator(5));
    })
  }

  // kelas romawi
  const namaKelas = document.querySelector('#namaKelas');
  const kelas = document.querySelector('#kelas');

  if (namaKelas != null) {
    namaKelas.addEventListener('keyup', () => {
      // change to uppermoon-case
      namaKelas.value = namaKelas.value.toUpperCase();

      let nama = namaKelas.value;
      nama = nama.toLowerCase().split(' ', 1);
      nama = nama[0];
      switch (nama) {
        case 'x':
          kelas.setAttribute('value', '10');
          break;
        case 'xi':
          kelas.setAttribute('value', '11');
          break;
        case 'xii':
          kelas.setAttribute('value', '12');
          break;
        default:
          kelas.setAttribute('value', 'Ketik salah satu berikut: (x, xi, xii)');
          break;
      }
    });
  }

  // siswa password default
  const newNis = document.querySelector('#newnis');
  const newpasswordforsiswa = document.querySelector('#newpasswordforsiswa');

  if (newNis != null) {
    newNis.addEventListener('keyup', () => {
      newpasswordforsiswa.setAttribute('value', `smkti@${newNis.value}.spp`) // smkti@nis.spp
    });
  }
  // ================================================

  // ajax mencari nis
  const nis = document.querySelector('#nis');
  const information = document.querySelector('#information');
  const jumlahbayar = document.querySelector('#jumlahbayar');
  if (nis != null) {
    nis.addEventListener('change', (e) => {
      let xhr = new XMLHttpRequest();
      let url = '../../controllers/Database.php';
      let params = `ns=${e.target.value}&func=getDetailSiswaByNis`; // ns = nis, func = function

      xhr.open('POST', url, true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
          const response = JSON.parse(xhr.response);
          information.innerHTML = `
        <fieldset>
          <legend>Data Siswa</legend>
          NIS: ${response.nis} <br>
          NAMA: ${response.nama} <br>
          NO TELP: ${response.no_telp} <br>
          KELAS: ${response.nama_kelas} <br>
          KOMPETENSI: ${response.kompetensi_keahlian} <br>

          HARGA DITENTUKAN: Rp${response.harga}
        </fieldset>
        `;
          jumlahbayar.setAttribute('value', response.harga);
        }
      }

      // execute
      xhr.send(params);
    })
  }

  // ajax status siswa
  const toggleActive = document.querySelector('#toggleActive');
  if (toggleActive != null) {
    toggleActive.addEventListener('click', () => {
      let checked;
      if (toggleActive.checked) {
        checked = 0;
      } else {
        checked = 1;
      }

      let xhr = new XMLHttpRequest();
      let url = '../../controllers/Database.php';
      let params = `ns=${nis.value}&func=setStatusSiswa&ic=${checked}`; // ic = is checked

      xhr.open('POST', url, true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
          // window.location.href = 'index.php';
          location.reload();
        }
      }

      xhr.send(params);
    })
  }
  // date
  const monthList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  const monthListNumber = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

  // ajax bulan dibayar 
  const tahun = document.querySelector('#years');
  const bulanDiBayar = document.querySelector('#bulanDiBayar');
  const cekBayar = document.querySelector('#cekbayar');

  if (tahun != null) {
    const idspp = bulanDiBayar.getAttribute('data-spp');
    tahun.addEventListener('change', () => {
      let xhr = new XMLHttpRequest();
      let url = '../../controllers/Database.php';
      let params = `idspp=${idspp}&tahun=${tahun.value}&func=findPembayaranByTahun`;

      xhr.open('POST', url, true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
          const response = JSON.parse(xhr.responseText);
          let child = '';
          response.forEach((v, i) => {
            child += v;
          });
          cekBayar.innerHTML = child;
        }
      }

      xhr.send(params);
    });
  }

  // cetak nota
  const cetakNotaBtn = document.querySelector('#cetakNota');
  const tableHistory = document.querySelector('#history');
  const namaSiswa = document.querySelector('#namaSiswa');
  const kelasSiswa = document.querySelector('#kelasSiswa');
  const nisSiswa = document.querySelector('#nisSiswa');

  let now = new Date();
  const dd = now.getDate();
  const mm = monthList[now.getMonth()];
  const yyyy = now.getFullYear();
  const h = now.getHours();
  const m = now.getMinutes();
  const s = now.getSeconds();
  now = `${dd} ${mm} ${yyyy} <br> (${h}:${m}:${s})`;

  if (cetakNotaBtn != null) {
    // https://developer.mozilla.org/en-US/docs/Web/CSS/@page
    // https://www.geeksforgeeks.org/how-to-remove-url-from-printing-the-page/
    cetakNotaBtn.addEventListener('click', () => {
      let th = window.open('', ''); // open new tab 
      th.document.write(`
      <html>
        <head>
          <title>Laporan Riwayat Pembayaran-NIS-${nisSiswa.innerHTML}</title>
          <style>
            @import url('../../../assets/css/cetak.css');
            @media print {
              @page {
                margin-top: 0;
                margin-bottom: 0;
              }
            }
          </style>
        </head>
        <body>
          <div class="header">
            <img src="https://elearning.smkti-baliglobal.sch.id//img/logo-ti2.png" alt="logo smk">
            <div class="header-info">
              <h4>Sekolah Menengah Kejuruan Teknologi Informasi Bali Global</h4>
              <h1>Smk TI Bali Global Denpasar</h1>
              <p>JL. Tukad Citarum No.44 Denpasar, Telp. (0361) 249434, Fax. (0361) 248269</p>
              <small>website : www.smkti-baliglobal.sch.id | email : admin@smkti-baliglobal.sch.id</small>
            </div>
            <small>${now}</small>
          </div>
          <span>${namaSiswa.innerHTML}</span>
          <span>${kelasSiswa.innerHTML}</span>
          <table>
            ${tableHistory.innerHTML}
          </table>
        </body>
      </html>
      `); // write something in new tab
      th.document.close(); // finishes writing
      // wait until page loaded successfully
      setTimeout(() => {
        th.print()
      }, 1000) // 1s
    });
  }

  // set default date (tglbayar)
  const tglbayar = document.querySelector('#tglbayar');
  const today = new Date();
  const mmNum = monthListNumber[today.getMonth()];
  if (tglbayar != null) {
    tglbayar.value = `${yyyy}-${mmNum}-${dd}`;
  }
})

// mobile
const mobileSearchBtn = document.querySelector('#mobileSearchBtn');
const formSearchMobile = document.querySelector('#formSearchMobile');

if (mobileSearchBtn != null) {
  mobileSearchBtn.addEventListener('click', () => {
    console.log(formSearchMobile.getAttribute('style'));
    formSearchMobile.classList.toggle('formvisible');
  });
}