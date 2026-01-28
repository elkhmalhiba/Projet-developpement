document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('#password');
    const toggleIcon = document.querySelector('#toggleIcon');
    const loginForm = document.querySelector('#loginForm');
    const loginBtn = document.querySelector('#loginBtn');

    // إظهار/إخفاء كلمة المرور
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleIcon.classList.toggle('bxs-show');
        toggleIcon.classList.toggle('bxs-hide');
    });

    // تأثير الـ Loading عند الضغط على الزر
    loginForm.addEventListener('submit', function() {
        loginBtn.querySelector('.btn-text').classList.add('d-none');
        loginBtn.querySelector('.spinner-border').classList.remove('d-none');
        loginBtn.disabled = true;
    });
});