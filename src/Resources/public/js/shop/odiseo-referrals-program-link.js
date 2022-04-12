/*
    This file is part of the Odiseo Referrals Plugin package, a commercial software.
    Only users who have purchased a valid license and accept to the terms of the License Agreement can install
    and use this program.
    Copyright (c) 2018-2022 - Pablo D'amico
*/

(function($) {
    if (document.querySelectorAll('[data-share-link-btn]')) {
        document.querySelectorAll('[data-share-link-btn]').forEach((element) => {
            element.addEventListener('click', function() {
                var text = element.innerHTML;
    
                if (this.dataset.copied) {
                    return copyAndReset(this, this.innerHTML, this.dataset.copied);
                }
    
                this.setAttribute('disabled', 'disabled');
                this.classList.add('loading');
                //this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    
                $.ajax({
                    type: 'POST',
                    url: this.dataset.href,
                    context: this,
                    dataType: 'html',
                    error() {
                        this.removeAttribute('disabled');
                        document.getElementById('sylius-cart-validation-error').innerText = 'Error!';
                        document.getElementById('sylius-cart-validation-error').classList.remove('d-none');
                        this.classList.remove('loading');
                    },
                    success(JSONresponse) {
                        response = JSON.parse(JSONresponse);
                        if (typeof response === 'object') {
                            this.dataset.copied = response.link;
                
                            copyAndReset(this, text, response.link);
                
                            this.removeAttribute('disabled');
                        }
                        else {
                            window.location.replace(response.responseURL);
                        }
                        this.classList.remove('loading');
                    },
                });            
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
})(jQuery);
