(function() {

    /**
     * Initialize unsplash image
     */
    function initialize() {
        let area  = $('meta[name="unsplash.area"]').attr('content'),
            image = JSON.parse($('meta[name="unsplash.image"]').attr('content'));

        document.body.style.setProperty('--unsplash-image-medium', `url(${image.image.medium.url})`);
        document.body.style.setProperty('--unsplash-image-small', `url(${image.image.small.url})`);
        document.body.style.setProperty('--unsplash-image-large', `url(${image.image.large.url})`);
        if(area === 'header' && $('#body-public').length === 0) {
            $('#appmenu').toggleClass('inverted', !image.isDark);
            createInforNotification(image);
        } else {
            createInfoPopup(image);
        }
    }

    /**
     * Create the popup info message for the login page
     *
     * @param image
     */
    function createInfoPopup(image) {
        let iconClass = image.isDark ? 'light':'dark',
            $button   = $(`<div id="unsplash-info" class="${iconClass}"></div>`);

        $button.append(getDetailsMessage(image, 'unsplash-details'));
        $button.click(function(e) {$(e.target).toggleClass('open');});

        $('body').append($button);
    }

    /**
     * Create the info notification for logged in users
     *
     * @param image
     */
    function createInforNotification(image) {
        $('.notifications-button').click(() => {
            if($('#unsplash-notification').length === 0) {
                let $menu    = $('.notification-container.menu'),
                    $details = getDetailsMessage(image, 'unsplash-notification');

                $menu.prepend($details);
                $menu.css('max-height', (260 + $details.outerHeight()) + 'px');
            }
        });
    }

    /**
     * Creates the info message
     *
     * @param image
     * @param id
     * @returns {HTMLElement}
     */
    function getDetailsMessage(image, id) {
        let $details = $(`<a id="${id}" style="background-image:url(${image.avatar.url})" href="${image.link}" target="_blank" rel="noreferrer noopener"></a>`);

        if(image.description && image.description.trim().length !== 0) $details.append(`<blockquote>&#8222;${image.description}&#8220;</blockquote>`);
        let text = OC.L10N.translate('unsplash', 'Image by {creator} from {provider}', image);
        $details.append('<div>' + text + '</div>');
        $details.attr('title', OC.L10N.translate('unsplash', 'See the full image and more details on {provider}', image));

        return $details;
    }

    initialize();
})();