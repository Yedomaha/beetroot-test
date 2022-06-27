export default () => {
    const dropDowns = document.querySelectorAll('[data-dropdown]');
    if (!dropDowns || dropDowns.length === 0) return;

    dropDowns.forEach((item) => {
        let dropDownBtn = item.querySelector('[data-dropdown-btn]');
        dropDownBtn.addEventListener('click', () => {
            if (window.innerWidth <= 1399) {
                clearActive();
                item.classList.toggle('active');
            }
        });
    });

    window.addEventListener('click', e => {
        const target = e.target;
        if (!target.closest('[data-dropdown]')) {
            clearActive();
        }
    })

    function clearActive() {
        let activeDropDowns = document.querySelectorAll('[data-dropdown].active');
        if (!activeDropDowns || activeDropDowns.length === 0) return;

        activeDropDowns.forEach((item) => {
            item.classList.remove('active');
        })
    }

}