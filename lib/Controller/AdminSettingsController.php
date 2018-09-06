<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\Cache\ImageCache;
use OCA\Unsplash\Services\AppSettingsService;
use OCA\Unsplash\Settings\AdminSettings;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

/**
 * Class AdminSettingsController
 *
 * @package OCA\Unsplash\Controller
 */
class AdminSettingsController extends Controller {

    /**
     * @var AppSettingsService
     */
    protected $settings;
    /**
     * @var ImageCache
     */
    private $imageCache;

    /**
     * PersonalSettingsController constructor.
     *
     * @param                 $appName
     * @param IRequest        $request
     * @param AppSettingsService $settings
     * @param ImageCache $imageCache
     */
    public function __construct($appName, IRequest $request, AppSettingsService $settings, ImageCache $imageCache) {
        parent::__construct($appName, $request);
        $this->settings = $settings;
        $this->imageCache = $imageCache;
    }

    /**
     * Update the app default settings
     *
     * @param string $key
     * @param        $value
     *
     * @return JSONResponse
     * @throws \OCP\Files\NotPermittedException
     */
    public function set(string $key, $value): JSONResponse {

        if($value === 'true') $value = true;
        if($value === 'false') $value = false;

        if($key === 'style/header') {
            $this->settings->setHeaderEnabled($value);
        } else if($key === 'style/login') {
            $this->settings->setLoginEnabled($value);
        } else if($key === 'api/query') {
            if(!in_array($value, AdminSettings::$apiQueryOptions)) $value = 'nature';
            $this->settings->setImageSubject($value);
            $this->imageCache->clear();
        } else if($key === 'api/key') {
            $this->settings->setApiKey($value);
            $this->imageCache->clear();
        } else {
            return new JSONResponse(['status' => 'error'], Http::STATUS_BAD_REQUEST);
        }

        return new JSONResponse(['status' => 'ok']);
    }
}