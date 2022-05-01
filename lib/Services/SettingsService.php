<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCP\IConfig;
use OCA\Unsplash\Provider\ProviderDefinitions;
use Psr\Log\LoggerInterface;


/**
 * Class SettingsService
 *
 * @package OCA\Unsplash\Services
 */
class SettingsService {

    const STYLE_LOGIN          = 'unsplash/style/login';
    const STYLE_HEADER         = 'unsplash/style/header';
    const STYLE_DASHBORAD      = 'unsplash/style/dashborad';
    const USER_STYLE_HEADER    = 'unsplash/style/header';
    const USER_STYLE_DASHBORAD = 'unsplash/style/dashborad';
    const PROVIDER_SELECTED    = 'unsplash/provider/selected';
    const PROVIDER_DEFAULT     = 'Unsplash';

    const STYLE_TINT_ALLOWED = 'unsplash/style/tint';
    const STYLE_STRENGHT_COLOR = 'unsplash/style/strength/color';
    const STYLE_STRENGHT_BLUR = 'unsplash/style/strength/blur';

    const STYLE_TINT_ALLOWED_DEFAULT = 0; //equals 30%
    const STYLE_STRENGHT_COLOR_DEFAULT = 30; //equals 30%
    const STYLE_STRENGHT_BLUR_DEFAULT = 0;

	private $headerbackgroundLinkDefault = 'https://source.unsplash.com/random/featured/';

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
     * @param IConfig     $config
     * @param Defaults     $defaults
     */
    public function __construct($userId, $appName, IConfig $config, \OC_Defaults $defaults) {
        $this->config = $config;
        $this->userId = $userId;
        if($this->config->getSystemValue('maintenance', false)) {
            $this->userId = null;
        }
        $this->appName = $appName;

        $this->providerDefinitions = new ProviderDefinitions($this->appName,$this->config);
		$this->defaults = $defaults;
    }

    /**
     * If the page header should be styled for this user
     *
     * @return bool
     */
    public function getUserStyleHeaderEnabled(): bool {
        $styleHeader = $this->config->getUserValue($this->userId, $this->appName, self::USER_STYLE_HEADER, null);

        if($styleHeader === null) return $this->getServerStyleHeaderEnabled();

        return $styleHeader == 1;
    }

    /**
     * Set if the page header should be styled for this user
     *
     * @param int $styleHeader
     *
     * @return void
     * @throws \OCP\PreConditionNotMetException
     */
    public function setUserStyleHeaderEnabled(int $styleHeader = 1) {
        $this->config->setUserValue($this->userId, $this->appName, self::USER_STYLE_HEADER, $styleHeader);
    }

    /**
     * If the dashboard should be styled for this user
     *
     * @return bool
     */
    public function getUserStyleDashboardEnabled(): bool {
        $styleHeader = $this->config->getUserValue($this->userId, $this->appName, self::USER_STYLE_DASHBORAD, null);

        if($styleHeader === null) return $this->getServerStyleDashboardEnabled();

        return $styleHeader == 1;
    }

    /**
     * Set if the dashboard should be styled for this user
     *
     * @param int $styleDashboard
     *
     * @return void
     * @throws \OCP\PreConditionNotMetException
     */
    public function setUserStyleDashboardEnabled(int $styleDashboard = 1) {
        $this->config->setUserValue($this->userId, $this->appName, self::USER_STYLE_DASHBORAD, $styleDashboard);
    }

    /**
     * If the page header should be styled by default
     *
     * @return bool
     */
    public function getServerStyleHeaderEnabled(): bool {
        return $this->config->getAppValue($this->appName, self::STYLE_HEADER, 1) == 1;
    }

    /**
     * Set if the page header should be styled by default
     *
     * @param int $styleHeader
     */
    public function setServerStyleHeaderEnabled(int $styleHeader = 1) {
        $this->config->setAppValue($this->appName, self::STYLE_HEADER, $styleHeader);
    }

    /**
     * If the page dashboard be styled by default
     *
     * @return bool
     */
    public function getServerStyleDashboardEnabled(): bool {
        return $this->config->getAppValue($this->appName, self::STYLE_DASHBORAD, 0) == 1;
    }

    /**
     * Set if the dashboard should be styled by default
     *
     * @param int $styleDashboard
     */
    public function setServerStyleDashboardEnabled(int $styleDashboard = 1) {
        $this->config->setAppValue($this->appName, self::STYLE_DASHBORAD, $styleDashboard);
    }

    /**
     * If the login page should be styled by default
     *
     * @return bool
     */
    public function getServerStyleLoginEnabled(): bool {
        return $this->config->getAppValue($this->appName, self::STYLE_LOGIN, 1) == 1;
    }

    /**
     * Set if the login page should be styled by default
     *
     * @param int $styleLogin
     */
    public function setServerStyleLoginEnabled(int $styleLogin = 1) {
        $this->config->setAppValue($this->appName, self::STYLE_LOGIN, $styleLogin);
    }

    /**
     * @return int
     */
    public function getNextcloudVersion(): int {
        $version = $this->config->getSystemValue('version', '0.0.0');
        $parts = explode('.', $version, 2);

        return intval($parts[0]);
    }

	/**
	 * Set the selected imageprovider
	 *
	 * @param string $providername
	 */
	public function setImageProvider(string $providername) {
		$this->config->setAppValue($this->appName, self::PROVIDER_SELECTED, $providername);
	}

	/**
	 * Get the selected imageprovider
	 *
	 * @param string $providername
	 * @return string current provider
	 */
	public function getImageProvider() {
		return $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, self::PROVIDER_DEFAULT);
	}

	/**
	 * Get all defined imageprovider
	 */
	public function getAllImageProvider() {
		return $this->providerDefinitions->getAllProviderNames();
	}


	/**
	 * Returns the URL to the custom Unsplash-path
	 *
	 * @return String
	 */
	public function headerbackgroundLink() {
		$providerName = $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, self::PROVIDER_DEFAULT);
		$provider = $this->providerDefinitions->getProviderByName($providerName);

		return $provider->getRandomImageUrl();
	}

	/**
	 * Get all URLs for whitelisting
	 */
	public function getWhitelistingUrlsForSelectedProvider() {
		$providerName = $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, self::PROVIDER_DEFAULT);
		$provider = $this->providerDefinitions->getProviderByName($providerName);
		return $provider->getWhitelistResourceUrls();
	}
	/**
	 * nextcloud theming main color
	 *
	 * @param String $styleUrl
	 */
	public function getInstanceColor() {
		return $this->config->getAppValue('theming', 'color', $this->defaults->getColorPrimary());
	}

	/**
	 * If the login page should be styled by default
	 *
	 * @return bool
	 */
	public function isTintAllowed(): bool {
		return $this->config->getAppValue($this->appName, self::STYLE_TINT_ALLOWED, self::STYLE_TINT_ALLOWED_DEFAULT);
	}

	public function setTintAllowed(int $tinting) {
		$this->config->setAppValue($this->appName, self::STYLE_TINT_ALLOWED, $tinting);
	}


	/**
	 * color strength
	 *
	 * @param String $styleUrl
	 */
	public function getColorStrength() {
		return $this->config->getAppValue($this->appName, self::STYLE_STRENGHT_COLOR, self::STYLE_STRENGHT_COLOR_DEFAULT);
	}

	/**
	 * set color strength
	 *
	 * @param String $styleUrl
	 */
	public function setColorStrength(int $strength) {
		if($strength>100){
			$strength=100;
		}
		if($strength<0){
			$strength=0;
		}
		$this->config->setAppValue($this->appName, self::STYLE_STRENGHT_COLOR, $strength);
	}


	/**
	 * blur strength
	 *
	 * @param String $styleUrl
	 */
	public function getBlurStrength() {
		return $this->config->getAppValue($this->appName, self::STYLE_STRENGHT_BLUR, self::STYLE_STRENGHT_BLUR_DEFAULT);
	}
	/**
	 * set blur strength
	 *
	 * @param String $styleUrl
	 */
	public function setBlurStrength(int $strength) {
		if($strength>25){
			$strength=25;
		}
		if($strength<0){
			$strength=0;
		}
		$this->config->setAppValue($this->appName, self::STYLE_STRENGHT_BLUR, $strength);
	}

	/**
	 * Returns the URL to the custom Unsplash-path
	 *
	 * @return String
	 */
	public function headerbackgroundLink() {
		$headerbackgroundLink = $this->config->getAppValue($this->appName, 'headerbackgroundlink', $this->headerbackgroundLinkDefault);

		if(isset($headerbackgroundLink) && $headerbackgroundLink!=""){
			return $headerbackgroundLink;
		}else{
			return $this->headerbackgroundLinkDefault;
		}

	}

}
