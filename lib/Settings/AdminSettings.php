<?php

declare(strict_types=1);

/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Settings;

use OCA\Unsplash\Services\SettingsService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IURLGenerator;
use OCP\Settings\ISettings;

/**
 * Class AdminSettings
 *
 * @package OCA\Unsplash\Settings
 */
class AdminSettings implements ISettings
{
    /**
     * AdminSection constructor.
     *
     * @param IURLGenerator $urlGenerator
     * @param SettingsService $settings
     */
    public function __construct(
        private IURLGenerator $urlGenerator,
        private SettingsService $settings,
    )
    {
    }

    /**
     * @return TemplateResponse returns the instance with all parameters set, ready to be rendered
     */
    public function getForm(): TemplateResponse
    {
        return new TemplateResponse('unsplash', 'settings/admin', [
            'saveSettingsUrl' => $this->urlGenerator->linkToRouteAbsolute('unsplash.admin_settings.set'),
            'requestCustomizationUrl' =>
                $this->urlGenerator->linkToRouteAbsolute(
                    'unsplash.admin_settings.getCustomization',
                    array('providername' => $this->settings->getImageProviderName())
                ),
            'styleLogin' => $this->settings->getServerStyleLoginEnabled(),
            'styleDashboard' => $this->settings->getServerStyleDashboardEnabled(),
            'hasDashboard' => $this->settings->getNextcloudVersion() > 19,
            'availableProvider' => $this->settings->getAllImageProvider(),
            'selectedProvider' => $this->settings->getImageProviderName(),
            'availableCustomizations' => $this->settings->getAllCustomizableImageProvider(),
            'selectionCustomization' => $this->settings->getImageProviderCustomization(),
            'styleTint' => $this->settings->isTintEnabled(),
            'styleStrengthColor' => $this->settings->getColorStrength(),
            'styleStrengthBlur' => $this->settings->getBlurStrength(),
            'styleHighVisibility' => $this->settings->isHighVisibilityLogin(),
            'isCached' => $this->settings->isCached(),
            'imageURL' => $this->settings->getSelectedImageProvider()->getCachedImageURL()
        ]);
    }

    /**
     * @return string the section ID, e.g. 'sharing'
     */
    public function getSection(): string
    {
        return 'theming';
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     * the admin section. The forms are arranged in ascending order of the
     * priority values. It is required to return a value between 0 and 100.
     */
    public function getPriority(): int
    {
        return 9;
    }
}
