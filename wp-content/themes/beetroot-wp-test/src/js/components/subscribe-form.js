import mailchimpSend from '../helpers/mailchimp-send';

export default () => {
    subscribeSection();

    function subscribeSection() {
        let formElems = document.querySelectorAll('.subscribe__form');
        if (!formElems || formElems.length === 0) return;

        formElems.forEach((item) => {
            mailchimpSend(item);
        });

    }

}