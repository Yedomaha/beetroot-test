export default () => {

    applyForm();

    function applyForm() {
        const forms = document.querySelectorAll('.apply form');
        if (!forms || forms.length === 0) return;

        forms.forEach((form) => {
            labelsAnimation(form);
            fileInputs(form);
        });

        function labelsAnimation(form) {
            const textInputs = form.querySelectorAll(`input[type='text'], input[type='email'], input[type='tel']`);
            if (!textInputs || textInputs.length === 0) return;

            textInputs.forEach((input) => {
                const inputLabel = input.closest('label');
                if (!inputLabel) return;
                input.addEventListener('focus', () => {
                    inputLabel.classList.add('active');
                });
                input.addEventListener('blur', () => {
                    if (!input.value || input.value === '') {
                        inputLabel.classList.remove('active');
                    }
                })
            })
        }

        function fileInputs(form) {
            const fileInputs = form.querySelectorAll(`input[type='file']`);
            if (!fileInputs || fileInputs.length === 0) return;

            fileInputs.forEach((input) => {
                const inputWrap = input.closest('.apply-form__file-input-wrap');
                const inputResultEl = inputWrap?.querySelector('.apply-form__file-input-result');
                if (!inputResultEl) return;

                input.addEventListener('change', () => {
                    if (input.value && input.value !== '') {
                        let fileName = getFileName(input.value);
                        inputResultEl.innerText = fileName;
                    } else {
                        inputResultEl.innerText = '';
                    }
                })
            })
        }

        function getFileName(fullPath) {
            if (fullPath) {
                let startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                let filename = fullPath.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                return filename;
            }
        }
    }
}