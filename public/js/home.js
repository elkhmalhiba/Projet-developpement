const themeToggle = document.getElementById('theme-toggle');
const themeIcon = document.getElementById('theme-icon');
const themeText = document.getElementById('theme-text');
const body = document.documentElement; // كنشدو الـ HTML كامل

// 1. تحقق واش المستخدم ديجا اختار شي Theme قبل
const currentTheme = localStorage.getItem('theme');
if (currentTheme) {
    body.setAttribute('data-theme', currentTheme);
    updateUI(currentTheme);
}

// 2. عند الضغط على الزر
themeToggle.addEventListener('click', () => {
    let theme = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    
    body.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme); // حفظ الاختيار
    updateUI(theme);
});

// 3. تحديث الأيقونة والنص
function updateUI(theme) {
    if (theme === 'dark') {
        themeIcon.classList.replace('bx-moon', 'bx-sun');
        themeText.innerText = "Mode Clair";
    } else {
        themeIcon.classList.replace('bx-sun', 'bx-moon');
        themeText.innerText = "Mode Sombre";
    }
}