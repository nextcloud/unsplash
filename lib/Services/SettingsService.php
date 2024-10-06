<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCA\Unsplash\ProviderHandler\CachedProvider;
use OCA\Unsplash\ProviderHandler\Provider;
use OCA\Unsplash\ProviderHandler\ProviderDefinitions;
use OCP\Files\IAppData;
use OCP\IConfig;
use Psr\Log\LoggerInterface;

/**
 * Class SettingsService
 *
 * @package OCA\Unsplash\Services
 */
class SettingsService
{

    const STYLE_LOGIN = 'unsplash/style/login';
    const STYLE_LOGIN_HIGH_VISIBILITY = 'unsplash/style/login/highvisibility';
    const STYLE_DASHBORAD = 'unsplash/style/dashborad';
    const USER_STYLE_DASHBORAD = 'unsplash/style/dashborad';
    const PROVIDER_SELECTED = 'unsplash/provider/selected';
    const PROVIDER_DEFAULT = 'Unsplash';

    const STYLE_TINT_ALLOWED = 'unsplash/style/tint';
    const STYLE_STRENGHT_COLOR = 'unsplash/style/strength/color';
    const STYLE_STRENGHT_BLUR = 'unsplash/style/strength/blur';

    const STYLE_TINT_ALLOWED_DEFAULT = 0; //equals 30%
    const STYLE_STRENGHT_COLOR_DEFAULT = 30; //equals 30%
    const STYLE_STRENGHT_BLUR_DEFAULT = 0;

    const STYLE_LOGIN_HIGH_VISIBILITY_DEFAULT = 0;

    /**
     * @var IConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $appName;

    /**
     * @var ProviderDefinitions
     */
    protected $providerDefinitions;

    /**
     * @var \OC_Defaults
     */
    private $defaults;

    /**
     * FaviconService constructor.
     *
     * @param string|null $userId
     * @param             $appName
     * @param IConfig $config
     * @param IAppData $config
     * @param Defaults $defaults
     * @param LoggerInterface $logger
     */
    public function __construct($userId, $appName, IConfig $config, IAppData $appData, \OC_Defaults $defaults, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->userId = $userId;
        if ($this->config->getSystemValue('maintenance', false)) {
            $this->userId = null;
        }
        $this->appName = $appName;

        $this->providerDefinitions = new ProviderDefinitions($this->appName, $logger, $this->config, $appData);
        $this->defaults = $defaults;
    }

    /**
     * If the dashboard should be styled for this user
     *
     * @return bool
     */
    public function getUserStyleDashboardEnabled(): bool
    {
        // for magic value see: https://github.com/nextcloud/server/blame/master/apps/theming/lib/Service/BackgroundService.php
        $themingSettingsKey = "background";
        if ($this->getNextcloudVersion() > 26) {
            $themingSettingsKey = "background_image";
        }
        $themingAppDashboard = $this->config->getUserValue($this->userId, "theming", $themingSettingsKey, 'default');

        // dont add custom css when custom image was selected
        if ($themingAppDashboard == 'default') {
            return $this->getServerStyleDashboardEnabled();
        }
        return false;
    }

    /**
     * Todo: refactor this function to a "has dash" function that also checks wether the dashboard is actually enabled.
     *       and then dont show the entries.
     * @return int
     */
    public function getNextcloudVersion(): int
    {
        $version = $this->config->getSystemValue('version', '0.0.0');
        $parts = explode('.', $version, 2);

        return intval($parts[0]);
    }

    /**
     * If the page dashboard be styled by default
     *
     * @return bool
     */
    public function getServerStyleDashboardEnabled(): bool
    {
        return $this->config->getAppValue($this->appName, self::STYLE_DASHBORAD, 0) == 1;
    }

    /**
     * Set if the dashboard should be styled by default
     *
     * @param int $styleDashboard
     */
    public function setServerStyleDashboardEnabled(int $styleDashboard = 1)
    {
        $this->config->setAppValue($this->appName, self::STYLE_DASHBORAD, $styleDashboard);
    }

    /**
     * If the login page should be styled by default
     *
     * @return bool
     */
    public function getServerStyleLoginEnabled(): bool
    {
        return $this->config->getAppValue($this->appName, self::STYLE_LOGIN, 1) == 1;
    }

    /**
     * Set if the login page should be styled by default
     *
     * @param int $styleLogin
     */
    public function setServerStyleLoginEnabled(int $styleLogin = 1)
    {
        $this->config->setAppValue($this->appName, self::STYLE_LOGIN, $styleLogin);
    }

    /**
     * Set the selected imageprovider, but does not use the provided string.
     *
     * @param string $providername
     */
    public function setImageProviderSanitized(string $providername): void
    {
        $allProvider = $this->getAllImageProvider();
        foreach ($allProvider as &$value) {
            if($value == $providername) {
                $this->config->setAppValue($this->appName, self::PROVIDER_SELECTED, $value);
            }
        }

    }

    /**
     * Get the selected imageprovider
     *
     * @return string name of the provider
     * @return string current provider
     */
    public function getImageProvider($name): Provider
    {
        return $this->providerDefinitions->getProviderByName($name);
    }

    /**
     * Get the selected imageprovider
     *
     * @return Provider current provider
     */
    public function getSelectedImageProvider(): Provider
    {
        $name = $this->getImageProviderName();
        return $this->providerDefinitions->getProviderByName($name);
    }

    /**
     * Get the selected imageprovider's name
     *
     * @return string current provider name
     */
    public function getImageProviderName(): string
    {
        return $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, self::PROVIDER_DEFAULT);
    }

    /**
     * Get the selected imageprovider customization
     *
     * @return string current provider customization
     */
    public function getImageProviderCustomization()
    {
        $providername = $this->getImageProviderName();
        $provider = $this->providerDefinitions->getProviderByName($providername);
        return $provider->getCustomSearchterms();
    }

    /**
     * Set the selected imageprovider customization
     *
     * @param string $customization
     */
    public function setImageProviderCustomization($customization)
    {
        $providername = $this->getImageProviderName();
        $provider = $this->providerDefinitions->getProviderByName($providername);
        $provider->setCustomSearchTerms($customization);
        $this->fetchIfRequired();
    }

    /**
     * Get all defined imageprovider
     * @return array[String]
     */
    public function getAllImageProvider(): array
    {
        return $this->providerDefinitions->getAllProviderNames();
    }

    /**
     * Get all defined imageprovider that allow customization
     */
    public function getAllCustomizableImageProvider()
    {
        $all = [];
        foreach ($this->providerDefinitions->getAllProviderNames() as $value) {
            $provider = $this->providerDefinitions->getProviderByName($value);
            if ($provider->isCustomizable()) {
                $all[] = $value;
            }
        }
        return $this->providerDefinitions->getAllProviderNames();
    }


    /**
     * Returns the URL to the custom Unsplash-path
     *
     * @return String
     */
    public function headerbackgroundLink($size)
    {
        $providerName = $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, self::PROVIDER_DEFAULT);
        $provider = $this->providerDefinitions->getProviderByName($providerName);

        if ($provider->isCached()) {
            return $provider->getCachedImageURL();
        }
        return $provider->getRandomImageUrl($size);
    }

    /**
     * Get all URLs for whitelisting
     */
    public function getWhitelistingUrlsForSelectedProvider()
    {
        $providerName = $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, self::PROVIDER_DEFAULT);
        $provider = $this->providerDefinitions->getProviderByName($providerName);
        return $provider->getWhitelistResourceUrls();
    }

    /**
     * nextcloud theming main color
     *
     * @param String $styleUrl
     */
    public function getInstanceColor()
    {
        return $this->config->getAppValue('theming', 'color', $this->defaults->getColorPrimary());
    }

    /**
     * If the login page should be styled by default
     *
     * @return bool
     */
    public function isTintEnabled(): bool
    {
        return $this->config->getAppValue($this->appName, self::STYLE_TINT_ALLOWED, self::STYLE_TINT_ALLOWED_DEFAULT);
    }

    public function setTint(int $tinting): void
    {
        $this->config->setAppValue($this->appName, self::STYLE_TINT_ALLOWED, $tinting);
    }


    /**
     * color strength
     *
     * @param String $styleUrl
     */
    public function getColorStrength()
    {
        return $this->config->getAppValue($this->appName, self::STYLE_STRENGHT_COLOR, self::STYLE_STRENGHT_COLOR_DEFAULT);
    }

    /**
     * set color strength
     *
     * @param String $styleUrl
     */
    public function setColorStrength(int $strength)
    {
        if ($strength > 100) {
            $strength = 100;
        }
        if ($strength < 0) {
            $strength = 0;
        }
        $this->config->setAppValue($this->appName, self::STYLE_STRENGHT_COLOR, $strength);
    }


    /**
     * blur strength
     *
     * @param String $styleUrl
     */
    public function getBlurStrength()
    {
        return $this->config->getAppValue($this->appName, self::STYLE_STRENGHT_BLUR, self::STYLE_STRENGHT_BLUR_DEFAULT);
    }

    /**
     * set blur strength
     *
     * @param String $styleUrl
     */
    public function setBlurStrength(int $strength)
    {
        if ($strength > 25) {
            $strength = 25;
        }
        if ($strength < 0) {
            $strength = 0;
        }
        $this->config->setAppValue($this->appName, self::STYLE_STRENGHT_BLUR, $strength);
    }

    /**
     * If the login page should be styled as High Visibility for Legal reasons
     *
     * @return bool
     */
    public function isHighVisibilityLogin(): bool
    {
        return $this->config->getAppValue($this->appName, self::STYLE_LOGIN_HIGH_VISIBILITY, self::STYLE_LOGIN_HIGH_VISIBILITY_DEFAULT);
    }

    public function setHighVisibilityLogin(int $highVisibility): void
    {
        $this->config->setAppValue($this->appName, self::STYLE_LOGIN_HIGH_VISIBILITY, $highVisibility);
    }

    /**
     * Store the authentication token for the current provider
     * @param string $token
     */
    public function setCurrentProviderToken(string $token)
    {
        $provider = $this->getImageProviderName();
        $this->config->setAppValue($this->appName, 'splash/provider/' . $provider . '/token', $token);
        $this->fetchIfRequired();
    }

    private function fetchIfRequired()
    {
        $provider = $this->getImageProviderName();
        $providerToFetch = $this->providerDefinitions->getProviderByName($provider);
        if ($providerToFetch->isCached()) {
            $providerToFetch->fetchCached();
        }

    }


    /**
     * Get new image for current provider
     */
    public function updateCachedBackground()
    {
        $provider = $this->getImageProviderName();
        $providerToFetch = $this->providerDefinitions->getProviderByName($provider);
        if ($providerToFetch->isCached()) {
            $providerToFetch->deleteCached();
            $providerToFetch->fetchCached();
        }
    }


    /**
     * Wrapper to check if current provider is cached
     */
    public function isCached(): bool
    {
        $provider = $this->getImageProviderName();
        return $this->providerDefinitions->getProviderByName($provider)->isCached();


    }

}
