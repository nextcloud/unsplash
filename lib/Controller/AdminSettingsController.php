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
 * Class AdminSettingsController
 *
 * @package OCA\Unsplash\Controller
 */
class AdminSettingsController extends Controller
{

    /**
     * @var SettingsService
     */
    protected $settings;

    /**
     * PersonalSettingsController constructor.
     *
     * @param                 $appName
     * @param IRequest $request
     * @param SettingsService $settings
     */
    public function __construct($appName, IRequest $request, SettingsService $settings)
    {
        parent::__construct($appName, $request);
        $this->settings = $settings;
    }

    /**
     * Update the app default settings
     *
     * @param string $key
     * @param        $value
     *
     * @return JSONResponse
     */
    public function set(string $key, $value): JSONResponse
    {
        if (strtolower($value) === 'true') $value = true;
        if (strtolower($value) === 'false') $value = false;

        if ($key === 'style/login') {
            $this->settings->setServerStyleLoginEnabled($value);
        } else if ($key === 'style/dashboard') {
            $this->settings->setServerStyleDashboardEnabled($value);
        } else if ($key === 'provider/provider') {
            //todo: do NOT store this value. Sanitize it! (check against available provider, and store one of them)
            $this->settings->setImageProvider(filter_var($value, FILTER_SANITIZE_STRING));
            $cached = $this->settings->isCached();
            return new JSONResponse(['status' => $value, "isCached" => $cached]);
        } else if ($key === 'provider/customization') {
            $this->settings->setImageProviderCustomization(filter_var($value, FILTER_SANITIZE_STRING));
        } else if ($key === 'style/tint') {
            if ($value) {
                $this->settings->setTint(1);
            } else {
                $this->settings->setTint(0);
            }
        } else if ($key === 'style/strength/color') {
            $this->settings->setColorStrength(filter_var($value, FILTER_SANITIZE_NUMBER_INT));
        } else if ($key === 'style/strength/blur') {
            $this->settings->setBlurStrength(filter_var($value, FILTER_SANITIZE_NUMBER_INT));
        } else if ($key === 'style/login/highvisibility') {
            if ($value) {
                $this->settings->setHighVisibilityLogin(1);
            } else {
                $this->settings->setHighVisibilityLogin(0);
            }
        } else if ($key === 'provider/token') {
            $this->settings->setCurrentProviderToken($value);
        } else if ($key === 'delete/cache') {
            $this->settings->updateCachedBackground();
        } else {
            return new JSONResponse(['status' => 'error'], Http::STATUS_BAD_REQUEST);
        }

        return new JSONResponse(['status' => $value]);
    }

    /**
     * Get the customizationstring for the requested providername
     *
     * @param string $providername
     *
     * @return JSONResponse
     */
    public function getCustomization(string $providername): JSONResponse
    {
        $provider = $this->settings->getImageProvider(filter_var($providername, FILTER_SANITIZE_STRING));
        return new JSONResponse(['status' => 'ok', 'customization' => $provider->getCustomSearchterms()]);
    }
}
