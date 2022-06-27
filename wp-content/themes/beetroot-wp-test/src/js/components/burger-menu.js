export default () => {
    const burgerBtn = document.querySelector('[data-burger-btn]');
    const body = document.querySelector('body');
    const burgerNavOverlay = document.querySelector('[data-burger-nav]');

    if (!burgerBtn || !body) return;

    burgerBtn.addEventListener('click', () => {
        burgerBtn.classList.toggle('open');
        body.classList.toggle('open-nav');
    })
}