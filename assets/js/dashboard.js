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

let now = new Date();
const dd = now.getDate();
const mm = monthList[now.getMonth()];
const yyyy = now.getFullYear();
const h = now.getHours();
const m = now.getMinutes();
const s = now.getSeconds();
now = `${dd} ${mm} ${yyyy} <br> (${h}:${m}:${s} WITA)`;

if (cetakNotaBtn != null) {
  cetakNotaBtn.addEventListener('click', () => {
    let th = window.open('', ''); // open new tab 
    th.document.write(`
      <html>
        <head>
          <title>Cetak Nota!</title>
          <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Roboto', sans-serif;
            }

            body {
                display: flex;
                flex-direction: column;
            }

            .header {
                display: flex;
                align-items: center;
                padding: 0 2rem;
                border: 1px solid black;
                border-bottom: none;
            }

            .header img {
                width: 6rem;
                flex-grow: 1;

            }

            .header h1 {
                flex-grow: 48;
                text-align: center;
            }

            .header small {
                flex-grow: 1;
            }

            /* table */
            table {
                border: 1px solid black;
                border-top: none;
                width: 100%;
                border-spacing: 10px;
                color: #11101D;
            }

            table td {
                border-bottom: 1px solid white;
                text-align: center;
                padding: 10px;
                border-spacing: 10px;
                position: relative;
                border-radius: 12px 12px 0 0;
                box-shadow: 0 10px 10px rgba(255, 255, 255, 0.1);
                text-transform: capitalize;
            }

            table td:after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 100%;
                height: 2px;
                z-index: 1;
                background: #1d1b31;
                transition-duration: .3s;
                transition-timing-function: ease-out;
                box-shadow: 0 10px 10px rgba(29, 27, 49, 0.2);
            }
          </style>
        </head>
        <body>
          <div class="header">
            <img src="../../../assets/img/profile.png" alt="logo smk">
            <h1>Nota</h1>
            <small>${now}</small>
          </div>
          <table>
            ${tableHistory.innerHTML}
          </table>
        </body>
      </html>
    `); // write something in new tab
    th.document.close(); // finishes writing
    th.print();
  });
}

// set default date (tglbayar)
const tglbayar = document.querySelector('#tglbayar');
const today = new Date();
const mmNum = monthListNumber[today.getMonth()];
tglbayar.value = `${yyyy}-${mmNum}-${dd}`;