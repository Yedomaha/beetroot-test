export default () => {

    initJobsFilterSection();

    function initJobsFilterSection() {
        let sections = document.querySelectorAll('.job-openings');
        if (!sections || sections.length === 0) return;

        sections.forEach((section) => {
            gridViewSwitchers(section);
            tagsListLogic(section);
            tagsItemsClick(section);
        })

    }

    function gridViewSwitchers(section) {
        const viewSwitchers = section.querySelectorAll('[data-grid-type]');
        const postsGrid = section.querySelector('.job-filter__grid');
        if (!viewSwitchers || viewSwitchers.length === 0 || !postsGrid) return;

        viewSwitchers.forEach((button) => {
            button.addEventListener('click', () => {
                changeActiveStatus(button);
                let gridType = button.getAttribute('data-grid-type');
                if (gridType === 'grid') {
                    postsGrid.classList.remove('job-filter__grid--list');
                } else if (gridType === 'list') {
                    postsGrid.classList.add('job-filter__grid--list');
                }
            });
        });

        function changeActiveStatus(currentBtn) {
            viewSwitchers.forEach((btn) => {
                btn.classList.remove('active')
            });
            currentBtn.classList.add('active');
        }
    }

    function tagsListLogic(section) {
        const showMoreBtn = section.querySelector(`[data-show='more']`);
        const showLessBtn = section.querySelector(`[data-show='less']`);

        const tagsList = section.querySelector('.tags-list');
        const tagsItems = tagsList?.querySelectorAll('.tags-list__item:not([data-view-switch]):not(.tags-list__item--count-to-show)');
        if (!showMoreBtn || !showLessBtn || !tagsItems || tagsItems.length === 0) return;

        showLess();
        tagsList.classList.remove('tags-list--disabled');

        showMoreBtn.addEventListener('click', () => {
            showMore();
        });

        showLessBtn.addEventListener('click', () => {
            showLess();
        });

        function showMore() {
            if (!tagsItems || tagsItems.length === 0) return;
            tagsItems.forEach((item) => {
                item.classList.remove('hidden');
            });
            showMoreBtn.closest('li').classList.add('hidden');
            showLessBtn.closest('li').classList.remove('hidden');
        }

        function showLess() {
            if (!tagsList || !tagsItems || tagsItems.length === 0) return;
            let itemHeight = tagsItems[0].clientHeight;

            if (tagsList.clientHeight < itemHeight * 2) {
                showLessBtn.closest('li').classList.add('hidden');
                showMoreBtn.closest('li').classList.add('hidden');
            } else {
                for (let i = tagsItems.length - 1; i > 0; i--) {
                    if (tagsList.clientHeight > itemHeight * 2) {
                        tagsItems[i].classList.add('hidden');
                    }
                }
                showLessBtn.closest('li').classList.add('hidden');
                showMoreBtn.closest('li').classList.remove('hidden');
            }
        }

    }

    function tagsItemsClick(section) {
        /*temp*/
        const tagsItems = section.querySelectorAll('.tags-list__btn:not([data-view-switch]):not([data-show])');
        if (!tagsItems || tagsItems.length === 0) return;

        tagsItems.forEach((item) => {
            item.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        })
    }
}