document.getElementById('submit-button').addEventListener('click', function(event) {
    const username = document.getElementById('InputUsername');
    const password = document.getElementById('InputPassword');
    const usernameValue = username.value.trim();
    const passwordValue = password.value.trim();

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

 
