<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Settings;

use OC_Defaults;
use OCA\Unsplash\Services\UserSettingsService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IURLGenerator;

/**
 * Class PersonalSettings
 *
 * @package OCA\Unsplash\Settings
 */
class PersonalSettings {

    /**
     * @var IURLGenerator
     */
    protected $urlGenerator;

    /**
     * @var UserSettingsService
     */
    protected $settings;

    /**
     * @var OC_Defaults
     */
    protected $theming;

    /**
     * AdminSection constructor.
     *
     * @param IURLGenerator       $urlGenerator
     * @param UserSettingsService $settings
     * @param OC_Defaults         $theming
     */
    public function __construct(IURLGenerator $urlGenerator, UserSettingsService $settings, OC_Defaults $theming) {
        $this->urlGenerator = $urlGenerator;
        $this->settings     = $settings;
        $this->theming      = $theming;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return TemplateResponse
     */
    public function getForm() {
        return new TemplateResponse('unsplash', 'settings/personal', [
            'saveSettingsUrl' => $this->urlGenerator->linkToRouteAbsolute('unsplash.personal_settings.set'),
            'styleHeader'     => $this->settings->isHeaderEnabled(),
            'keepImage'       => $this->settings->imagePersistenceEnabled(),
            'label'           => $this->theming->getEntity()
        ], '');
    }
}