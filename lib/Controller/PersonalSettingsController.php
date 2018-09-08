<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\Services\UserSettingsService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

/**
 * Class PersonalSettingsController
 *
 * @package OCA\Unsplash\Controller
 */
class PersonalSettingsController extends Controller {

    /**
     * @var UserSettingsService
     */
    protected $settings;

    /**
     * PersonalSettingsController constructor.
     *
     * @param                     $appName
     * @param IRequest            $request
     * @param UserSettingsService $settings
     */
    public function __construct($appName, IRequest $request, UserSettingsService $settings) {
        parent::__construct($appName, $request);
        $this->settings = $settings;
    }

    /**
     * Update user settings
     *
     * @NoAdminRequired
     *
     * @param string  $key The key of the setting
     * @param  string $value The new value of the setting
     *
     * @return JSONResponse
     * @throws \OCP\PreConditionNotMetException
     */
    public function set(string $key, $value): JSONResponse {

        if($value === 'true') $value = true;
        if($value === 'false') $value = false;

        if($key === 'style/header') {
            $this->settings->setHeaderEnabled($value);
        } else if($key === 'image/persistence') {
            $this->settings->setImagePersistenceEnabled($value);
        } else {
            return new JSONResponse(['status' => 'error'], Http::STATUS_BAD_REQUEST);
        }

        return new JSONResponse(['status' => 'ok']);
    }
}