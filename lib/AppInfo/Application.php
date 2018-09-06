<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\AppInfo;

use OC;
use OCA\Unsplash\Services\AppSettingsService;
use OCA\Unsplash\Services\RandomImageService;
use OCA\Unsplash\Services\UserSettingsService;
use OCP\AppFramework\App;
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
     * Add the stylesheets
     *
     * @throws \OCP\AppFramework\QueryException
     */
    public function registerStyleSheets() {

        if(!OC::$server->getUserSession()->isLoggedIn()) {
            /** @var AppSettingsService $settings */
            $settings = $this->getContainer()->query(AppSettingsService::class);

            if($settings->isLoginEnabled()) $this->addMetaTags('login');
        } else {
            /** @var UserSettingsService $settings */
            $settings = $this->getContainer()->query(UserSettingsService::class);

            if($settings->isHeaderEnabled()) $this->addMetaTags('header');
        }
    }

    /**
     * @param string $area
     *
     * @throws \OCP\AppFramework\QueryException
     */
    public function addMetaTags(string $area) {

        /** @var RandomImageService $randomImageService */
        $randomImageService = $this->getContainer()->query(RandomImageService::class);

        $imageInfo = $randomImageService->getRandomImage();
        if(empty($imageInfo)) return;

        $infoArray           = $imageInfo->toArray();
        $infoArray['url']    = OC::$server->getURLGenerator()->linkToRoute('unsplash.Image.background', ['uuid' => $imageInfo->getUuid()]);
        $infoArray['avatar'] = OC::$server->getURLGenerator()->linkToRoute('unsplash.Image.avatar', ['uuid' => $imageInfo->getUuid()]);

        Util::addHeader('meta', ['name' => 'unsplash.image', 'content' => json_encode($infoArray)]);
        Util::addHeader('meta', ['name' => 'unsplash.area', 'content' => $area]);

        Util::addStyle('unsplash', $area);
        Util::addScript('unsplash', 'unsplash');
    }

}