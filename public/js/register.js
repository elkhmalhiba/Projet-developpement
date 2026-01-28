
function toggle(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bx-hide', 'bx-show');
    } else {
        input.type = 'password';
        icon.classList.replace('bx-show', 'bx-hide');
    }
}

document.getElementById('pass').addEventListener('input', function(e) {
    const strengthBar = document.getElementById('strengthBar');
    const val = e.target.value;
    let width = 0;
    let color = "";

    if (val.length > 5) width = 33, color = "#ef4444"; // ضعيف
    if (val.length > 8 && /[0-9]/.test(val)) width = 66, color = "#f59e0b"; // متوسط
    if (val.length > 10 && /[A-Z]/.test(val)) width = 100, color = "#10b981"; // قوي

    strengthBar.style.width = width + '%';
    strengthBar.style.backgroundColor = color;
});