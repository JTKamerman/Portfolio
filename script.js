// script.js

// ========== THEME TOGGLE ==========
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'light') {
  document.body.classList.add('light-theme');
}

const themeToggleBtn = document.getElementById('themeToggle');
if (themeToggleBtn) {
  themeToggleBtn.addEventListener('click', () => {
    document.body.classList.toggle('light-theme');
    if (document.body.classList.contains('light-theme')) {
      localStorage.setItem('theme', 'light');
    } else {
      localStorage.setItem('theme', 'dark');
    }
  });
}

// If you have a separate button in mobile-header:
const mobileThemeBtn = document.getElementById('mobileThemeBtn');
if (mobileThemeBtn) {
  mobileThemeBtn.addEventListener('click', () => {
    document.body.classList.toggle('light-theme');
    if (document.body.classList.contains('light-theme')) {
      localStorage.setItem('theme', 'light');
    } else {
      localStorage.setItem('theme', 'dark');
    }
  });
}

// ========== SIDEBAR TOGGLE FOR MOBILE ==========
const sidebar = document.querySelector('.sidebar');
const menuBtn = document.getElementById('mobileMenuBtn');

if (menuBtn && sidebar) {
  menuBtn.addEventListener('click', () => {
    // Toggle .opened class
    sidebar.classList.toggle('opened');
  });
}
