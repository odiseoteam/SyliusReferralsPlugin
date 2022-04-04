import axios from 'axios';

// Global axios settings
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
axios.defaults.headers.post.accept = 'application/json, text/javascript, */*; q=0.01';
axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';

if (document.querySelectorAll('[data-share-link-btn]')) {
    document.querySelectorAll('[data-share-link-btn]').forEach((element) => {
        element.addEventListener('click', function() {
            var text = element.innerHTML;

            if (this.dataset.copied) {
                return copyAndReset(this, this.innerHTML, this.dataset.copied);
            }

            this.setAttribute('disabled', 'disabled');
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

            axios.post(this.dataset.href, null)
                .then((response) => {
                    if (typeof response.data === 'object') {
                        this.dataset.copied = response.data.link;

                        copyAndReset(this, text, response.data.link);

                        this.removeAttribute('disabled');
                    }
                    else {
                        window.location.replace(response.request.responseURL);
                    }
                })
                .catch(() => {
                    this.removeAttribute('disabled');

                    document.getElementById('sylius-cart-validation-error').innerText = 'Error!';
                    document.getElementById('sylius-cart-validation-error').classList.remove('d-none');
                    element.closest('form').classList.remove('loading');
                });
            ;
        });
    });

    function copyAndReset(element, text, link) {
        element.innerHTML = 'Copied to clipboard';

        setTimeout(() => {
            element.innerHTML = text;
        }, 2000);

        navigator.clipboard.writeText(link);
    }
}
