import applicationForm from './components/alication-form';
import setMarginBottomToMain from './components/footer';
import jobsFilter from './components/jobs-filter';

export default () => {

    setMarginBottomToMain();
    applicationForm();
    jobsFilter();

}