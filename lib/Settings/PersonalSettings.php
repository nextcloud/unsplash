<?php

declare(strict_types=1);

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
 * Class PersonalSettings
 *
 * @package OCA\Unsplash\Controller\Settings
 */
class PersonalSettings implements ISettings
{

    /**
     * PersonalSection constructor.
     *
     * @param SettingsService $settings
     * @param OC_Defaults $theming
     */
    public function __construct(
        private SettingsService $settings,
        private OC_Defaults $theming,
    )
    {
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return TemplateResponse
     */
    public function getForm(): TemplateResponse
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
        ], TemplateResponse::RENDER_AS_BLANK);
    }

    /**
     * @return string
     */
    public function getSection(): string
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
