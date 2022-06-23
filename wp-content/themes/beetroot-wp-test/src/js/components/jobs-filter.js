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
        const viewSwitchers = section.querySelectorAll('.tags-list__grid-type');
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
        const tabsList = section.querySelector('.tags-list');
        const tagsItems = tabsList?.querySelectorAll('.tags-list__item:not([data-view-switch]):not([data-show])');
        if (!showMoreBtn || !showLessBtn || !tagsItems || tagsItems.length === 0) return;

        showMoreBtn.addEventListener('click', () => {
            showMore(tagsItems);
        });

        showLessBtn.addEventListener('click', () => {
            showLess(tabsList, tagsItems);
        });

        function showMore(tagsItems) {
            if (!tagsItems || tagsItems.length === 0) return;
            tagsItems.forEach((item) => {
                item.classList.remove('hidden');
            });
            showMoreBtn.classList.add('active');
            showLessBtn.classList.remove('active');
        }

        function showLess(tabsList, tagsItems) {
            if (!tabsList || !tagsItems || tagsItems.length === 0) return;

            let itemHeight = tagsItems[0].clientHeight;

            for (let i = tagsItems.length - 1; i > 0; i--) {
                if (tabsList.clientHeight > itemHeight * 2 && !tagsItems[i].classList.contains('active')) {
                    tagsItems[i].classList.add('hidden');
                }
            }

            showLessBtn.classList.add('active');
            showMoreBtn.classList.remove('active');
        }

    }

    function tagsItemsClick(section){
        /*temp*/
        const tagsItems = section.querySelectorAll('.tags-list__item:not([data-view-switch]):not([data-show])');
        if(!tagsItems || tagsItems.length === 0) return;

        tagsItems.forEach((item)=>{
            item.addEventListener('click',()=>{
                item.classList.toggle('active');
            });
        })
    }
}