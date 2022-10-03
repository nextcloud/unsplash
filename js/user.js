(function() {
    function initialize() {
        this._timer = [];
        this.saveUrl = document.getElementById('unsplash-settings').dataset.save;

        let settings = document.querySelectorAll('[data-setting]');
        for(let setting of settings) {
            setting.addEventListener(
                'change',
                (e) => {
                    let key   = e.target.dataset.setting,
                        value = e.target.value,
                        type  = e.target.getAttribute('type');

                    if(type === 'checkbox') {
                        value = e.target.checked ? 'true':'false';
                    }

                    if(type === 'select') {
                        value = e.target.value;
                    }

                    if(key === 'style/tint') {
                        console.log("change"+value)
                        let enable = false
                        if(value === "true"){
                            enable = true
                        }
                        document.getElementById('unsplash-style-color-strenght').disabled = !enable;
                    }

                    _setValue(key, value, type);
                }
            );
        }
    }

    /**
     * Update configuration value
     *
     * @param key
     * @param value
     * @private
     */
    function _setValue(key, value, type) {
        let headers = new Headers();
        headers.append('requesttoken', OC.requestToken);
        headers.append('Accept', 'application/json');
        headers.append('Content-Type', 'application/json');

        let body    = JSON.stringify({key, value}),
            options = {headers, body, method: 'POST', redirect: 'error'},
            request = new Request(this.saveUrl, options);

        fetch(request)
            .then(() => {
                _showMessage('success');
                if(type === 'select') {
                    getCustomization(value)
                }
            })
            .catch((e) => {
                console.error(e);
                _showMessage('error');
            });
    }

    /**
     * Show save success/fail message
     *
     * @param type
     * @private
     */
    function _showMessage(type) {
        let element = document.querySelector(`#unsplash-settings .msg.${type}`);

        element.classList.remove('active');
        element.classList.add('active');

        clearTimeout(this._timer[type]);
        this._timer[type] = setTimeout(() => { element.classList.remove('active'); }, 1000);
    }

    initialize();
})();
