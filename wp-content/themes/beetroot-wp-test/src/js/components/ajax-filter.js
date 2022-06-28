import axios from 'axios';
import qs from 'qs';
import throttle from 'lodash/throttle';

export default () => {
    const filterSection = document.querySelector('[data-filter-wrap]');
    const filterGrid = document.querySelector('[data-filter-grid]');
    const loadMoreButton = document.querySelector('[data-filter-load-more]');
    if (!filterSection || !filterGrid || !loadMoreButton) return;

    const loader = `<div class="loader"></div>`;
    const noPostsText = `<div class="noPostsText">No posts found.</div>`;
    const filterCheckboxes = filterSection.querySelectorAll(`input[type='checkbox'][data-filter]`);
    const filterSearchInput = filterSection.querySelector(`[data-filter='search']`);
    const filterButtons = filterSection.querySelectorAll(`button[data-filter]`);
    const buttonText = loadMoreButton.innerText;
    const ajaxURL = filterGrid.getAttribute('data-ajax-url');
    const postsPerPage = filterGrid.getAttribute('data-posts-per-page');

    let timer;
    let searchInputLastValue;
    let page = 1;
    let data = {action: 'jobs_filter_ajax', page, postsPerPage};
    let isStatusLoading = false;

    const hideButton = () => {
        loadMoreButton.classList.add('hide');
    };

    const showButton = () => {
        loadMoreButton.classList.remove('hide');
    };

    const hideLoader = () => {
        let loader = filterGrid.querySelector('.loader');
        loader ? loader.remove() : false;
        filterGrid.classList.remove('showLoader');
        filterSection.classList.remove('filter-disabled');
    };

    const showLoader = () => {
        filterGrid.innerHTML = loader;
        filterGrid.classList.add('showLoader');
        filterSection.classList.add('filter-disabled');
    };

    const noPostsStatusOn = () => {
        filterGrid.innerHTML = noPostsText;
        filterGrid.classList.add('noPosts');
    };

    const noPostsStatusOff = () => {
        let noPostText = filterGrid.querySelector('.noPostsText');
        noPostText ? noPostText.remove() : false;
        filterGrid.classList.remove('noPosts');
    };

    const clearGrid = () => {
        filterGrid.innerHTML = '';
        page = 1;
        data = {action: 'jobs_filter_ajax', page, postsPerPage}
    };

    const setButtonStatus = isLoading => {
        if (isLoading) {
            isStatusLoading = true;
            loadMoreButton.innerText = 'Loading...';
            loadMoreButton.classList.add('active');
        } else {
            hideLoader();
            isStatusLoading = false;
            loadMoreButton.innerText = buttonText;
            loadMoreButton.classList.remove('active');
        }
    };

    const insertPost = posts => {
        filterGrid.insertAdjacentHTML('beforeend', posts);
    };

    const buttonVisibilityLogic = (maxPage) => {
        if (maxPage && data.page) {
            if (data.page >= maxPage) hideButton();
        }
    };

    /*------*/

    const setDropDownPlaceholders = () => {
        let dropDownWraps = filterSection.querySelectorAll('.search-bar__drop-down-wrap');
        if (!dropDownWraps || dropDownWraps.length === 0) return;

        dropDownWraps.forEach((ddWrap) => {
            let placeholder = ddWrap.querySelector('.search-bar__drop-down-btn');
            let placeholderDefaultText = placeholder.dataset.placeholder;
            let activeCheckboxes = ddWrap.querySelectorAll(`input[type='checkbox'][data-filter]:checked`);
            let placeholderArr = [];
            if (!activeCheckboxes) return;

            activeCheckboxes.forEach((item) => {
                placeholderArr.push(item.dataset.name);
            });

            let uniArr = [...new Set(placeholderArr)];
            let placeholderString = uniArr.join(', ');

            if (placeholderString && placeholderString !== '') {
                placeholder.innerText = placeholderString;
            } else {
                placeholder.innerText = placeholderDefaultText;
            }
        })
    };

    const filterParams = (data) => {
        let activeCheckboxes = filterSection.querySelectorAll(`input[type='checkbox'][data-filter]:checked`);
        let activeButtons = filterSection.querySelectorAll(`button[data-filter].active`);
        let searchQuery = filterSearchInput.value.length >= 3 ? filterSearchInput.value : '';
        let departmentsArr = [];
        let locationsOfficeArr = [];
        let locationsAcademyArr = [];
        let technologiesArr = [];
        let paramsString = '';

        if (activeCheckboxes && activeCheckboxes.length > 0) {
            activeCheckboxes.forEach((item) => {
                let currentFilter = item.dataset.filter;
                let currentValue = item.dataset.value;
                if (currentFilter === 'department') {
                    departmentsArr.push(currentValue);
                } else if (currentFilter === 'locations_office') {
                    locationsOfficeArr.push(currentValue);
                } else if (currentFilter === 'locations_academy') {
                    locationsAcademyArr.push(currentValue);
                }
            })
        }

        if (activeButtons && activeButtons.length > 0) {
            activeButtons.forEach((item) => {
                let currentFilter = item.dataset.filter;
                let currentValue = item.dataset.value;
                if (currentFilter === 'technology') {
                    technologiesArr.push(currentValue);
                }
            })
        }

        data[`search`] = searchQuery;
        data[`departments`] = departmentsArr;
        data[`locations_office`] = locationsOfficeArr;
        data[`locations_academy`] = locationsAcademyArr;
        data[`technologies`] = technologiesArr;

        addParam('search', searchQuery);
        addParam('departments', departmentsArr.join(','));
        addParam('locations_office', locationsOfficeArr.join(','));
        addParam('locations_academy', locationsAcademyArr.join(','));
        addParam('technologies', technologiesArr.join(','));

        function addParam(paramName, paramValue) {
            if (!paramValue || !paramName) return;
            if (paramsString) {
                paramsString = `${paramsString}&${paramName}=${paramValue}`;
            } else {
                paramsString = `?${paramName}=${paramValue}`;
            }
        }

        updateUrlParams(paramsString);
        setDropDownPlaceholders();
    };

    const filterChange = e => {
        if (e) {
            e.preventDefault();
        }
        showButton();
        clearGrid();
        filterParams(data);
        if (isStatusLoading) return;
        showLoader();
        setButtonStatus(true);

        axios({
            async: false,
            method: 'post',
            url: ajaxURL,
            data: qs.stringify(data),
        }).then(response => {
            const response_data = response.data;
            const posts = response_data.posts;
            const maxPage = response_data.max_pages;
            const postsCount = response_data.posts_count;

            buttonVisibilityLogic(maxPage);
            setPostsCount(postsCount);

            if (posts && posts.trim() !== '') {
                noPostsStatusOff();
                insertPost(posts);
                setButtonStatus(false);
                loadMoreActivation();
            } else {
                setButtonStatus(false);
                hideButton();
                noPostsStatusOn();
            }
        }).catch(() => {
            console.log('catch 1');
            hideButton();
        });
    };

    const loadMoreClick = e => {
        if (e) {
            e.preventDefault();
        }
        if (isStatusLoading) return;
        data.page++;
        filterParams(data);
        // console.log(data);
        setButtonStatus(true);
        axios({
            async: false,
            method: 'post',
            url: ajaxURL,
            data: qs.stringify(data),
        }).then(response => {
            const response_data = response.data;
            // console.log(response_data);
            const posts = response_data.posts;
            let maxPage = response_data.max_pages;
            buttonVisibilityLogic(maxPage);
            if (posts && posts.trim() !== '') {
                insertPost(posts);
                setButtonStatus(false);
                loadMoreActivation();
            } else {
                setButtonStatus(false);
                hideButton();
            }
        }).catch(() => {
            console.log('catch');
            hideButton();
        });
    };

    const getUrlParams = () => {
        let urlParams;
        (window.onpopstate = function () {
            let match,
                pl = /\+/g,  // Regex for replacing addition symbol with a space
                search = /([^&=]+)=?([^&]*)/g,
                decode = function (s) {
                    return decodeURIComponent(s.replace(pl, " "));
                },
                query = window.location.search.substring(1);

            urlParams = {};
            while (match = search.exec(query))
                urlParams[decode(match[1])] = decode(match[2]);
        })();

        return urlParams;

    };

    const updateUrlParams = (paramsString) => {
        let urlParams = window.location.protocol + "//" + window.location.host + window.location.pathname + paramsString;
        window.history.pushState({path: urlParams}, '', urlParams);
    };

    const filterFromUrlParams = () => {
        let params = getUrlParams();

        let searchQuery = params['search'] ? params['search'] : false;
        let departmentsArr = params['departments'] ? params['departments'].split(',') : false;
        let locationsOfficeArr = params['locations_office'] ? params['locations_office'].split(',') : false;
        let locationsAcademyArr = params['locations_academy'] ? params['locations_academy'].split(',') : false;
        let technologiesArr = params['technologies'] ? params['technologies'].split(',') : false;
        if (searchQuery) {
            filterSearchInput.value = searchQuery;
        }
        if (departmentsArr) {
            departmentsArr.forEach((term_id) => {
                let currentCheckbox = filterSection.querySelector(`input[type='checkbox'][data-filter='department'][data-value='${term_id}']`);
                if (currentCheckbox) {
                    currentCheckbox.checked = true;
                }
            })
        }
        if (locationsOfficeArr) {
            locationsOfficeArr.forEach((term_id) => {
                let currentCheckbox = filterSection.querySelector(`input[type='checkbox'][data-filter='locations_office'][data-value='${term_id}']`);
                if (currentCheckbox) {
                    currentCheckbox.checked = true;
                }
            })
        }
        if (locationsAcademyArr) {
            locationsAcademyArr.forEach((term_id) => {
                let currentCheckbox = filterSection.querySelector(`input[type='checkbox'][data-filter='locations_academy'][data-value='${term_id}']`);
                if (currentCheckbox) {
                    currentCheckbox.checked = true;
                }
            })
        }
        if (technologiesArr) {
            technologiesArr.forEach((term_id) => {
                let currentBtn = filterSection.querySelector(`button[data-filter='technology'][data-value='${term_id}']`);
                currentBtn.classList.add('active')
            })
        }

        filterChange();

    };

    const selectDeselectAllLogic = () => {
        let checkboxesGroupWraps = filterSection.querySelectorAll('.search-bar__drop-down-col');
        if (!checkboxesGroupWraps || checkboxesGroupWraps.length === 0) return;

        checkboxesGroupWraps.forEach((cbgWrap) => {
            let checkboxes = cbgWrap.querySelectorAll(`input[type='checkbox'][data-filter]`);
            if (!checkboxes || checkboxes.length === 0) return;

            let changeTrigger = cbgWrap.querySelector(`[data-change]`);
            let selectAllText = changeTrigger.getAttribute('data-select-all');
            let deselectAllText = changeTrigger.getAttribute('data-hide-all');
            setChangeBtnText(cbgWrap);

            changeTrigger.addEventListener('click', () => {
                let activeCheckboxes = cbgWrap.querySelectorAll(`input[type='checkbox'][data-filter]:checked`);
                if (activeCheckboxes && activeCheckboxes.length > 0) {
                    activeCheckboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    })
                } else {
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = true;
                    });
                }
                setChangeBtnText(cbgWrap);
                filterChange();
            });

            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', () => {
                    setChangeBtnText(cbgWrap)
                })
            });

            function setChangeBtnText(checkboxesGroupWrap) {
                let activeCheckboxes = checkboxesGroupWrap.querySelectorAll(`input[type='checkbox'][data-filter]:checked`);
                if (activeCheckboxes && activeCheckboxes.length > 0) {
                    changeTrigger.innerText = deselectAllText;
                } else {
                    changeTrigger.innerText = selectAllText;
                }
            }

        })
    };

    const setPostsCount = (postsCount) => {
        let postsCounter = filterSection.querySelector(`[data-posts-counter]`);
        if (postsCounter) {
            if (postsCount === 1) {
                postsCounter.innerText = postsCount + ' opening';
            } else {
                postsCounter.innerText = postsCount + ' openings';
            }
        }
    };

    const loadMoreActivation = () =>{
        if (isStatusLoading) return;
        let x = window.innerHeight - 50;
        let loadMorePos = loadMoreButton.getBoundingClientRect().top;
        if(x >= loadMorePos && !loadMoreButton.classList.contains('hide')){
            loadMoreClick();
        }
    };


    /*Triggers logic*/

    filterCheckboxes.forEach((item) => {
        item.addEventListener('change', (e) => {
            filterChange(e);

            // let keyPause = 500;
            // clearTimeout(timer);
            // timer = setTimeout(function () {
            //     filterChange(e);
            // }, keyPause);
        });
    });
    filterButtons.forEach((item) => {
        item.addEventListener('click', (e) => {
            item.classList.toggle('active');
            filterChange(e);

            // let keyPause = 500;
            // clearTimeout(timer);
            // timer = setTimeout(function () {
            //     filterChange(e);
            // }, keyPause);
        });
    });
    filterSearchInput.addEventListener('keyup', (e) => {
        let keyPause = 500;
        if (filterSearchInput.value.length < 3) return;
        clearTimeout(timer);
        timer = setTimeout(checkLastVal, keyPause);

        function checkLastVal() {
            if (searchInputLastValue !== filterSearchInput.value) {
                filterChange(e);
                searchInputLastValue = filterSearchInput.value;
            }
        }
    });
    filterSearchInput.addEventListener('change', (e) => {
        if (searchInputLastValue !== filterSearchInput.value) {
            filterChange(e);
            searchInputLastValue = filterSearchInput.value;
        }
    });

    /*todo:*/

    window.addEventListener('scroll', throttle(function () {
        loadMoreActivation();
    }, 100));

    loadMoreButton.addEventListener('click', loadMoreClick);

    filterFromUrlParams();
    selectDeselectAllLogic();

    filterSection.classList.add('filterInit');

}