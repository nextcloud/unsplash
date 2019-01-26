<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCP\IConfig;
use OCA\Unsplash\Provider\ProviderDefinitions;

/**
 * Class SettingsService
 *
 * @package OCA\Unsplash\Services
 */
class SettingsService {

    const PROVIDER_SELECTED       = 'unsplash/provider/selected';
    const STYLE_LOGIN       = 'unsplash/style/login';
    const STYLE_HEADER      = 'unsplash/style/header';
    const USER_STYLE_HEADER = 'unsplash/style/header';

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
     * FaviconService constructor.
     *
     * @param string|null $userId
     * @param             $appName
     * @param IConfig     $config
     */
    public function __construct($userId, $appName, IConfig $config) {
        $this->config = $config;
        $this->userId = $userId;
        if($this->config->getSystemValue('maintenance', false)) {
            $this->userId = null;
        }
        $this->appName = $appName;

        $this->providerDefinitions = new ProviderDefinitions($this->appName,$this->config);
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
		return $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, "Unsplash");
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
		$providerName = $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, "Unsplash");
		$provider = $this->providerDefinitions->getProviderByName($providerName);

		return $provider->getRandomImageUrl();
	}

	/**
	 * Get all URLs for whitelisting
	 */
	public function getWhitelistingUrls() {
		$providerName = $this->config->getAppValue($this->appName, self::PROVIDER_SELECTED, "Unsplash");
		$provider = $this->providerDefinitions->getProviderByName($providerName);
		return $provider->getWhitelistResourceUrls();
	}
}