<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCP\IConfig;

/**
 * Class AppSettingsService
 *
 * @package OCA\Unsplash\Services
 */
class AppSettingsService {

    const STYLE_LOGIN  = 'unsplash/style/login';
    const STYLE_HEADER = 'unsplash/style/header';
    const IMAGE_TOPIC  = 'unsplash/image/subject';
    const IMAGE_AMOUNT = 'unsplash/image/amount';
    const API_KEY      = 'unsplash/api/key';

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
    }

    /**
     * If the page header should be styled by default
     *
     * @return bool
     */
    public function isHeaderEnabled(): bool {
        return $this->config->getAppValue($this->appName, self::STYLE_HEADER, 1) == 1;
    }

    /**
     * Set if the page header should be styled by default
     *
     * @param int $styleHeader
     */
    public function setHeaderEnabled(int $styleHeader = 1) {
        $this->config->setAppValue($this->appName, self::STYLE_HEADER, $styleHeader);
    }

    /**
     * If the login page should be styled by default
     *
     * @return bool
     */
    public function isLoginEnabled(): bool {
        return $this->config->getAppValue($this->appName, self::STYLE_LOGIN, 1) == 1;
    }

    /**
     * Set if the login page should be styled by default
     *
     * @param bool $styleLogin
     */
    public function setLoginEnabled(bool $styleLogin = true) {
        $this->config->setAppValue($this->appName, self::STYLE_LOGIN, $styleLogin ? 1:0);
    }

    /**
     * Get the subject of the images to use
     *
     * @return string
     */
    public function getImageSubject(): string {
        return $this->config->getAppValue($this->appName, self::IMAGE_TOPIC, 'any');
    }

    /**
     * Set the subject of the images to use
     *
     * @param string $subject
     */
    public function setImageSubject(string $subject) {
        $this->config->setAppValue($this->appName, self::IMAGE_TOPIC, $subject);
    }

    /**
     * Get the amount of images to be prefetch
     *
     * @return int
     */
    public function getImageAmount(): int {
        return $this->config->getAppValue($this->appName, self::IMAGE_AMOUNT, 20);
    }

    /**
     * Set the amount of images to be prefetch
     *
     * @param int $amount
     */
    public function setImageAmount(int $amount) {
        $this->config->setAppValue($this->appName, self::IMAGE_AMOUNT, $amount);
    }

    /**
     * Get the api key to use for the unsplash api
     *
     * @return string
     */
    public function getApiKey(): string {
        return $this->config->getAppValue($this->appName, self::API_KEY, 'ed3b10c059c5da43fe16c34ce5467b6c472abc10d21c498a6159df9fb556930a');
    }

    /**
     * Set the api key to use for the unsplash api
     *
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey) {
        $this->config->setAppValue($this->appName, self::API_KEY, $apiKey);
    }

}