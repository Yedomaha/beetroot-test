export default (form) => {

    form.addEventListener('submit', e => {
        e.preventDefault();

        // if(form.querySelector('.js-validate-robot').value !== '') { return false }

        const url = form.action;

        let data = '';
        const inputs = form.querySelectorAll('.subscribe__form-input');
        inputs.forEach((input, i) => {
            data += `&${input.name}=${encodeURIComponent(input.value)}`;

            input.addEventListener('input', () => {
                form.classList.remove('error')
            })
        });

        const script = document.createElement('script');
        script.src = url + data;
        document.body.appendChild(script);

        const callback = 'callback';
        window[callback] = function(data) {
            delete window[callback];
            document.body.removeChild(script);

            if (data.result == 'success') {
                form.classList.add('success');
                inputs.forEach((item) => {
                    item.value = '';
                });

                setTimeout(() => {
                    form.classList.remove('success');
                }, 10000)
            } else {
                form.classList.add('error')
            }
        };
    })
}
