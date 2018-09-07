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

    const STYLE_LOGIN       = 'unsplash/style/login';
    const STYLE_HEADER      = 'unsplash/style/header';
    const IMAGE_SUBJECT     = 'unsplash/image/subject';
    const IMAGE_AMOUNT      = 'unsplash/image/amount';
    const IMAGE_PROVIDER    = 'unsplash/image/provider';
    const IMAGE_PERSISTENCE = 'unsplash/image/persistence';
    const USER_SUBJECT      = 'unsplash/user/subject';
    const API_KEY           = 'unsplash/api/key';

    /**
     * @var IConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $appName;

    /**
     * AppSettingsService constructor.
     *
     * @param         $appName
     * @param IConfig $config
     */
    public function __construct($appName, IConfig $config) {
        $this->config  = $config;
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
     * @param bool $styleHeader
     */
    public function setHeaderEnabled(bool $styleHeader = true) {
        $this->config->setAppValue($this->appName, self::STYLE_HEADER, $styleHeader ? 1:0);
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
        return $this->config->getAppValue($this->appName, self::IMAGE_SUBJECT, 'any');
    }

    /**
     * Set the subject of the images to use
     *
     * @param string $subject
     */
    public function setImageSubject(string $subject) {
        $this->config->setAppValue($this->appName, self::IMAGE_SUBJECT, $subject);
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
     * Get the service to provide images
     *
     * @return string
     */
    public function getImageProvider(): string {
        return $this->config->getAppValue($this->appName, self::IMAGE_PROVIDER, 'unsplash');
    }

    /**
     * Set the service to provide images
     *
     * @param string $provider
     */
    public function setImageProvider(string $provider) {
        $this->config->setAppValue($this->appName, self::IMAGE_PROVIDER, $provider);
    }

    /**
     * If the image should be persistent throughout the whole session
     *
     * @return bool
     */
    public function imagePersistenceEnabled(): bool {
        $styleHeader = $this->config->getAppValue($this->appName, self::IMAGE_PERSISTENCE, 1);

        return $styleHeader == 1;
    }

    /**
     * Set if the image should be persistent throughout the whole session
     *
     * @param bool $imagePersistence
     *
     * @return void
     */
    public function setImagePersistenceEnabled(bool $imagePersistence = true) {
        $this->config->setAppValue($this->appName, self::IMAGE_PERSISTENCE, $imagePersistence ? 1:0);
    }

    /**
     * If the users are allowed to set their own subject
     *
     * @return bool
     */
    public function allowUserSubjects(): bool {
        return $this->config->getAppValue($this->appName, self::USER_SUBJECT, 0) == 1;
    }

    /**
     * Set if the users are allowed to set their own subject
     *
     * @param bool $allowUserSubject
     */
    public function setAllowUserSubjects(bool $allowUserSubject = false) {
        $this->config->setAppValue($this->appName, self::USER_SUBJECT, $allowUserSubject);
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