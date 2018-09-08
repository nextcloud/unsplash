<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\Cache\ImageCache;
use OCA\Unsplash\Services\AppSettingsService;
use OCA\Unsplash\Services\ImageFetchingService;
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
    protected $imageCache;

    /**
     * @var ImageFetchingService
     */
    protected $imageFetchingService;

    /**
     * PersonalSettingsController constructor.
     *
     * @param                      $appName
     * @param IRequest             $request
     * @param AppSettingsService   $settings
     * @param ImageCache           $imageCache
     * @param ImageFetchingService $imageFetchingService
     */
    public function __construct($appName, IRequest $request, AppSettingsService $settings, ImageCache $imageCache, ImageFetchingService $imageFetchingService) {
        parent::__construct($appName, $request);
        $this->settings             = $settings;
        $this->imageCache           = $imageCache;
        $this->imageFetchingService = $imageFetchingService;
    }

    /**
     * Update the global app settings
     *
     * @param string $key   The setting key
     * @param string $value The new value
     *
     * @return JSONResponse
     * @throws \OCP\AppFramework\QueryException
     */
    public function set(string $key, $value): JSONResponse {

        if($value === 'true') $value = true;
        if($value === 'false') $value = false;

        if($key === 'style/header') {
            $this->settings->setHeaderEnabled($value);
        } else if($key === 'style/login') {
            $this->settings->setLoginEnabled($value);
        } else if($key === 'image/persistence') {
            $this->settings->setImagePersistenceEnabled($value);
        } else if($key === 'api/query') {
            $subjects = $this->imageFetchingService->getImageProvider()->getSubjects();
            if(!in_array($value, $subjects)) $value = $subjects[0];
            $this->settings->setImageSubject($value);
        } else if($key === 'api/key') {
            $this->settings->setApiKey($value);
        } else {
            return new JSONResponse(['status' => 'error'], Http::STATUS_BAD_REQUEST);
        }

        return new JSONResponse(['status' => 'ok']);
    }
}