const toggleButton = document.querySelector('.menu-toggle');
const nav = document.querySelector('.main-nav');

if (toggleButton && nav) {
    toggleButton.addEventListener('click', () => {
        const willOpen = nav.classList.contains('hidden');
        nav.classList.toggle('hidden');
        nav.classList.toggle('flex');
        toggleButton.setAttribute('aria-expanded', String(willOpen));
    });
}
