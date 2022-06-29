export default () => {
    const dropDowns = document.querySelectorAll('[data-dropdown]');
    if (!dropDowns || dropDowns.length === 0) return;

    dropDowns.forEach((item) => {
        let dropDownBtn = item.querySelector('[data-dropdown-btn]');
        dropDownBtn.addEventListener('click', () => {
            if (window.innerWidth <= 1399) {
                if(item.classList.contains('active')){
                    item.classList.remove('active');
                } else{
                    closeActive();
                    item.classList.add('active');
                }
            }
        });
    });

    window.addEventListener('click', e => {
        const target = e.target;
        if (!target.closest('[data-dropdown]')) {
            closeActive();
        }
    })

    function closeActive() {
        let activeDropDowns = document.querySelectorAll('[data-dropdown].active');
        if (!activeDropDowns || activeDropDowns.length === 0) return;

        activeDropDowns.forEach((item) => {
            item.classList.remove('active');
        })
    }

}