<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Settings;

use OC_Defaults;
use OCA\Unsplash\Services\SettingsService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

/**
 * Class PersonalSettingsController
 *
 * @package OCA\Unsplash\Controller\Settings
 */
class PersonalSettings implements ISettings
{

    /**
     * @var SettingsService
     */
    protected $settings;

    /**
     * @var OC_Defaults
     */
    protected $theming;


    /**
     * AdminSection constructor.
     *
     * @param SettingsService $settings
     * @param OC_Defaults $theming
     */
    public function __construct(SettingsService $settings, OC_Defaults $theming)
    {
        $this->settings = $settings;
        $this->theming = $theming;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return TemplateResponse
     */
    public function getForm()
    {

        $dashboard = $this->settings->getServerStyleDashboardEnabled();
        $login = $this->settings->getServerStyleLoginEnabled();

        if ($this->settings->getImageProviderName() == "Nextcloud Image") {
            $dashboard = false;
            $login = false;
        }

        return new TemplateResponse('unsplash', 'settings/personal', [
            'selectedProvider' => str_replace(' ', '', $this->settings->getImageProviderName()),
            'label' => $this->theming->getEntity(),
            'dashboard' => $dashboard,
            'login' => $login,
        ], '');
    }

    /**
     * @return string
     */
    public function getSection()
    {
        return "theming";
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 75;
    }
}
