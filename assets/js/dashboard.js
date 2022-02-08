let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");
// let searchBtn = document.querySelector(".bx-search");

closeBtn.addEventListener("click", () => {
  sidebar.classList.toggle("open");
  menuBtnChange(); // calling the function(optional)
});

// searchBtn.addEventListener("click", () => { // Sidebar open when you click on the search iocn
//   sidebar.classList.toggle("open");
//   menuBtnChange(); // calling the function(optional)
// });

// following are the code to change sidebar button(optional)
function menuBtnChange() {
  if (sidebar.classList.contains("open")) {
    closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); // replacing the icons class
  } else {
    closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); // replacing the icons class
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

// ajax
const nis = document.querySelector('#nis');
const information = document.querySelector('#information');
if (nis != null) {
  nis.addEventListener('change', (e) => {
    let xhr = new XMLHttpRequest();
    let url = '../../controllers/Database.php';
    let params = `ns=${e.target.value}&func=getDetailSiswaByNis`; // ns = nis, func = function

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = () => {
      if (xhr.readyState == 4 && xhr.status == 200) {
        information.innerHTML = `
        <fieldset>
          <legend>Data Siswa</legend>
          ${xhr.responseText}
        </fieldset>
        `;
      }
    }

    // execute
    xhr.send(params);
  })
}

// cetak nota
const cetakNotaBtn = document.querySelector('#cetakNota');
const tableHistory = document.querySelector('#history');

// date
const monthList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
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

    // console.log(tableHistory);

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