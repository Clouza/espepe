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
// const passGenButton = document.querySelector('#passgen');
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

// passGenButton.addEventListener('click', () => {
//   passField.setAttribute('value', passwordGenerator(5));
// })

// ajax
const nis = document.querySelector('#nis');
const information = document.querySelector('#information');
nis.addEventListener('change', (e) => {
  let xhr = new XMLHttpRequest();
  let url = '../../controllers/Database.php';
  let params = `ns=${e.target.value}&func=getDetailSiswaByNis`; // ns = nis, func = function

  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      information.innerHTML = `<h1>Data Siswa</h1> ${xhr.responseText}`;
    }
  }

  // execute
  xhr.send(params);
})