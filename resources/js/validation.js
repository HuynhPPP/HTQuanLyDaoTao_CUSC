document.getElementById('InputEmail1').addEventListener('input', function() {
    const username = this.value.trim();
    if (username.length < 5) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

document.getElementById('InputPassword1').addEventListener('input', function() {
    const password = this.value.trim();
    if (password.length < 8) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

document.getElementById('InputCaptcha').addEventListener('input', function() {
    const captcha = this.value.trim();
    if (captcha.length === 0) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});