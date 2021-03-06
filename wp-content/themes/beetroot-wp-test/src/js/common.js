import gsap from 'gsap';
import axios from 'axios';
import qs from 'qs';
import throttle from 'lodash/throttle';

import applicationForm from './components/alication-form';
import setMarginBottomToMain from './components/footer';
import jobsFilter from './components/jobs-filter';
import ajaxFilter from './components/ajax-filter';
import burgerMenu from './components/burger-menu';
import dropDowns from './components/drop-downs';
import langSwitcher from './components/lang-switcher';
import anchorLinks from './components/anchor-links';
import subscribeForm from './components/subscribe-form';

export default () => {

    burgerMenu();
    setMarginBottomToMain();
    applicationForm();
    jobsFilter();
    ajaxFilter(axios, qs, throttle);
    dropDowns();
    langSwitcher();
    anchorLinks(gsap);
    subscribeForm();

}