<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\Services\SettingsService;
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
     * @var SettingsService
     */
    protected $settings;

    /**
     * PersonalSettingsController constructor.
     *
     * @param                 $appName
     * @param IRequest        $request
     * @param SettingsService $settings
     */
    public function __construct($appName, IRequest $request, SettingsService $settings) {
        parent::__construct($appName, $request);
        $this->settings = $settings;
    }

    /**
     * Update user settings
     *
     * @NoAdminRequired
     *
     * @param string $key
     * @param        $value
     *
     * @return JSONResponse
     * @throws \OCP\PreConditionNotMetException
     */
    public function set(string $key, $value): JSONResponse {

        if($value === 'true') $value = true;
        if($value === 'false') $value = false;

        if($key === 'style/header') {
            $this->settings->setUserStyleHeaderEnabled($value);
        } else {
            return new JSONResponse(['status' => 'error'], Http::STATUS_BAD_REQUEST);
        }

        return new JSONResponse(['status' => 'ok']);
    }
}