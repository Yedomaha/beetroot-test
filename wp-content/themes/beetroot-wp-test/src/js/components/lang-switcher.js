export default () => {
    const langSwitchers = document.querySelectorAll('[data-lang-switch]');
    if (!langSwitchers || langSwitchers.length === 0) return;

    langSwitchers.forEach((item) => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 1399) {
                // closeActive();
                item.classList.toggle('active');
            }
        });
    });

    window.addEventListener('click', e => {
        const target = e.target;
        if (!target.closest('[data-lang-switch')) {
            closeActive();
        }
    })

    function closeActive() {
        let activeDropDowns = document.querySelectorAll('[data-lang-switch].active');
        if (!activeDropDowns || activeDropDowns.length === 0) return;

        activeDropDowns.forEach((item) => {
            item.classList.remove('active');
        })
    }

}