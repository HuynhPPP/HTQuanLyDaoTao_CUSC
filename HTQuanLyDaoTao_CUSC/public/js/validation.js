/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************!*\
  !*** ./resources/js/validation.js ***!
  \************************************/
document.getElementById('submit-button').addEventListener('click', function (event) {
  var username = document.getElementById('InputUsername');
  var password = document.getElementById('InputPassword');
  var usernameValue = username.value.trim();
  var passwordValue = password.value.trim();
  if (usernameValue === '') {
    username.classList.add('is-invalid');
    username.nextElementSibling.innerText = 'Vui lòng nhập tài khoản.';
    event.preventDefault();
  } else {
    username.classList.remove('is-invalid');
    username.nextElementSibling.innerText = '';
  }
  if (passwordValue === '') {
    password.classList.add('is-invalid');
    password.nextElementSibling.innerText = 'Vui lòng nhập mật khẩu.';
    event.preventDefault();
  } else {
    password.classList.remove('is-invalid');
    password.nextElementSibling.innerText = '';
  }
});
/******/ })()
;