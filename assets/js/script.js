document.addEventListener('DOMContentLoaded', function () {
        grecaptcha.ready(function () {
            grecaptcha.execute(simple_recaptcha.site_key, {
                action: 'validate_captcha'
            })
                .then(function (token) {
                    if (document.getElementById('g-recaptcha-response')) {
                        document.getElementById('g-recaptcha-response').value = token;
                    }
                });
        });
});