const burgerMenu = document.querySelector('.burger-menu');
const topbarNav = document.querySelector('.topbar-nav');

burgerMenu.addEventListener('click', () => {
  burgerMenu.classList.toggle('active');
  topbarNav.classList.toggle('active');
});