<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\AppInfo;

use OC;
use OCA\Unsplash\Services\AppSettingsService;
use OCA\Unsplash\Services\UserImageService;
use OCA\Unsplash\Services\UserSettingsService;
use OCP\AppFramework\App;
use OCP\AppFramework\QueryException;
use OCP\Util;

/**
 * Class Application
 *
 * @package OCA\Unsplash\AppInfo
 */
class Application extends App {

    /**
     * Application constructor.
     *
     * @param array $urlParams
     */
    public function __construct(array $urlParams = []) {
        parent::__construct('unsplash', $urlParams);
    }

    /**
     * Register all app functionality
     *
     * @throws \OCP\AppFramework\QueryException
     */
    public function register() {
        $this->registerPersonalSettings();
        $this->registerStyleSheets();
    }

    /**
     * Add the personal settings page
     */
    public function registerPersonalSettings() {
        \OCP\App::registerPersonal('unsplash', 'templates/personal');
    }

    /**
     * Decides which stylesheets should be added
     *
     * @throws \OCP\AppFramework\QueryException
     */
    public function registerStyleSheets() {

        if(!OC::$server->getUserSession()->isLoggedIn()) {
            /** @var AppSettingsService $settings */
            $settings = $this->getContainer()->query(AppSettingsService::class);

            if($settings->isLoginEnabled()) $this->addMetaTags('login', $settings->getImageSubject());
        } else {
            /** @var UserSettingsService $settings */
            $settings = $this->getContainer()->query(UserSettingsService::class);

            if($settings->isHeaderEnabled()) $this->addMetaTags('header', $settings->getImageSubject());
        }
    }

    /**
     * Adds stylesheets and image data to the page
     *
     * @param string $area
     * @param string $subject
     *
     * @throws QueryException
     */
    public function addMetaTags(string $area, string $subject) {
        /** @var UserImageService $imageService */
        $imageService = $this->getContainer()->query(UserImageService::class);

        try {
            $imageInfo = $imageService->getUserImage($subject);
            if(empty($imageInfo)) return;
        } catch(\Throwable $e) {
            \OC::$server->getLogger()->logException($e);

            return;
        }

        $infoArray = $imageInfo->toArray();
        Util::addHeader('meta', ['name' => 'unsplash.image', 'content' => json_encode($infoArray)]);
        Util::addHeader('meta', ['name' => 'unsplash.area', 'content' => $area]);

        Util::addStyle('unsplash', $area);
        Util::addScript('unsplash', 'unsplash');
    }

}