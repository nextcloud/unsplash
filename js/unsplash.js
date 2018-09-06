(function() {
    function initialize() {
        let area  = $('meta[name="unsplash.area"]').attr('content'),
            image = JSON.parse($('meta[name="unsplash.image"]').attr('content'));

        if(area == 'header') {
            loadHeaderImage(image);
        } else {
            loadLoginImage(image);
        }
    }

    function loadHeaderImage(image) {
        document.body.style.setProperty('--unsplash-header-image', `url(${image.url})`);
        $('#appmenu').toggleClass('inverted', !image.isDark);
    }

    function loadLoginImage(image) {
        document.body.style.setProperty('--unsplash-login-image', `url(${image.url})`);
        createElement(image);
    }

    function createElement(image) {
        let iconClass = image.isDark ? 'light':'dark',
            $button   = $('<div id="unsplash-info" class="' + iconClass + '"></div>'),
            $details  = $('<a id="unsplash-details" style="background-image:url(' + image.avatar + ')" href="' + image.link + '" target="_blank" rel="noreferrer noopener"></a>');

        if(image.description && image.description.trim().length !== 0) $details.append('<blockquote>&#8222;' + image.description + '&#8220;</blockquote>');
        let text = OC.L10N.translate('unsplash', 'Image by {creator} from {provider}', image);
        $details.append('<div>' + text + '</div>');

        $button.append($details);
        $button.click(function(e) {$(e.target).toggleClass('open');});

        $('body').append($button);
    }

    initialize();
})();