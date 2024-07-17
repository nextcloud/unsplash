(function () {
    function initialize() {
        this._timer = [];
        this.saveUrl = document.getElementById('unsplash-settings').dataset.save;

        let customizationInputText = document.getElementById('splash-provider-customization');
        customizationInputText.onkeydown = function (e) {
            if (e.keyCode === 13) {
                let key = e.target.dataset.setting,
                    value = e.target.value,
                    type = e.target.getAttribute('type');
                _setValue(key, value, type);
                e.preventDefault();
            }
            return true;
        };

        let refreshCacheButton = document.getElementById('splash-provider-refresh-cache');
        refreshCacheButton.onclick = function (e) {
            _setValue(refreshCacheButton.dataset.setting)
            return true;
        };

        let settings = document.querySelectorAll('[data-setting]');
        for (let setting of settings) {
            setting.addEventListener(
                'change',
                (e) => {
                    let key = e.target.dataset.setting,
                        value = e.target.value,
                        type = e.target.getAttribute('type');

                    if (type === 'checkbox') {
                        value = e.target.checked ? 'true' : 'false';
                    }

                    if (type === 'select') {
                        value = e.target.value;
                    }

                    if (key === 'style/tint') {
                        let enable = false
                        if (value === "true") {
                            enable = true
                        }
                        document.getElementById('unsplash-style-color-strength').disabled = !enable;
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

        let headers = {
            method: 'POST',
            headers: {
                'requesttoken': OC.requestToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        }

        let body = JSON.stringify({key, value})
        let options = {headers, body, method: 'POST', redirect: 'error'}
        let request = new Request(this.saveUrl, options);

        fetch(request, headers)
            .then(response=> {
                _showMessage('success');
                if (type === 'select') {
                    getCustomization(value)
                }
                response.json().then( data => {
                    if(data.hasOwnProperty("isCached")) {
                        if(data.isCached) {
                            document.getElementById('splash-provider-refresh-cache').style.display = null;
                        } else {
                            document.getElementById('splash-provider-refresh-cache').style.display = "none";
                        }
                    }

                    if(data.hasOwnProperty("provider")) {
                        let token = document.getElementById('unsplash-providertoken')
                        if(data.provider === "UnsplashAPI") {
                            token.style.display = null;
                        } else {
                            token.style.display = "none";
                        }
                    }

                    if(data.hasOwnProperty("imageURL")) {
                        let preview = document.getElementById("unsplash-preview")
                        preview.src = data.imageURL

                    }

                })
            })
            .catch((e) => {
                console.error(e);
                _showMessage('error');
            });
    }

    /**
     * Update configuration value
     *
     * @param key
     * @param value
     * @private
     */
    function getCustomization(providername) {
        let headers = {
            method: 'GET',
            headers: {
                'requesttoken': OC.requestToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        }

        let updateCustomizationUrl = document.getElementById('unsplash-settings').dataset.requestupdate

        //todo: build this url better.
        let apiUrl = updateCustomizationUrl.substr(0, updateCustomizationUrl.lastIndexOf("/") + 1);
        let options = {headers, method: 'GET', redirect: 'error'}
        let request = new Request(apiUrl + providername, options);

        fetch(request, headers)
            .then(res =>
                res.json())
            .then(data => {
                document.getElementById('splash-provider-customization').value = data['customization'];
            })
            .then(() => {
                _showMessage('success');
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
        this._timer[type] = setTimeout(() => {
            element.classList.remove('active');
        }, 1000);
    }

    initialize();
})();
