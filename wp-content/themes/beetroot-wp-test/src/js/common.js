import applicationForm from './components/alication-form';
import setMarginBottomToMain from './components/footer';
import jobsFilter from './components/jobs-filter';
import ajaxFilter from './components/ajax-filter';

export default () => {

    setMarginBottomToMain();
    applicationForm();
    jobsFilter();
    ajaxFilter();

}