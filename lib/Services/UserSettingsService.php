<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCP\IConfig;

/**
 * Class UserSettingsService
 *
 * @package OCA\Unsplash\Services
 */
class UserSettingsService {

    const STYLE_HEADER      = 'unsplash/style/header';
    const IMAGE_SUBJECT     = 'unsplash/image/subject';
    const IMAGE_PERSISTENCE = 'unsplash/image/persistence';

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
     * @var AppSettingsService
     */
    protected $appSettings;

    /**
     * UserSettingsService constructor.
     *
     * @param                    $userId
     * @param                    $appName
     * @param IConfig            $config
     * @param AppSettingsService $appSettings
     */
    public function __construct($userId, $appName, IConfig $config, AppSettingsService $appSettings) {
        $this->config      = $config;
        $this->userId      = $userId;
        $this->appName     = $appName;
        $this->appSettings = $appSettings;
    }

    /**
     * If the page header should be styled for this user
     *
     * @return bool
     */
    public function isHeaderEnabled(): bool {
        $styleHeader = $this->config->getUserValue($this->userId, $this->appName, self::STYLE_HEADER, null);

        if($styleHeader === null) return $this->appSettings->isHeaderEnabled();

        return $styleHeader == 1;
    }

    /**
     * Set if the page header should be styled for this user
     *
     * @param bool $styleHeader
     *
     * @return void
     * @throws \OCP\PreConditionNotMetException
     */
    public function setHeaderEnabled(bool $styleHeader = true) {
        $this->config->setUserValue($this->userId, $this->appName, self::STYLE_HEADER, $styleHeader ? 1:0);
    }

    /**
     * If the image should be persistent throughout the whole session
     *
     * @return bool
     */
    public function imagePersistenceEnabled(): bool {
        $styleHeader = $this->config->getUserValue($this->userId, $this->appName, self::IMAGE_PERSISTENCE, null);

        if($styleHeader === null) return $this->appSettings->imagePersistenceEnabled();

        return $styleHeader == 1;
    }

    /**
     * Set if the image should be persistent throughout the whole session
     *
     * @param bool $imagePersistence
     *
     * @return void
     * @throws \OCP\PreConditionNotMetException
     */
    public function setImagePersistenceEnabled(bool $imagePersistence = true) {
        $this->config->setUserValue($this->userId, $this->appName, self::IMAGE_PERSISTENCE, $imagePersistence ? 1:0);
    }

    /**
     * Get the subject of the images to use
     *
     * @return string
     */
    public function getImageSubject(): string {
        $subject = $this->config->getUserValue($this->userId, $this->appName, self::IMAGE_SUBJECT, null);

        if($subject === null) return $this->appSettings->getImageSubject();

        return $subject == 1;
    }

    /**
     * Set the subject of the images to use
     *
     * @param string $subject
     *
     * @throws \OCP\PreConditionNotMetException
     */
    public function setImageSubject(string $subject) {
        $this->config->setUserValue($this->userId, $this->appName, self::IMAGE_SUBJECT, $subject);
    }
}