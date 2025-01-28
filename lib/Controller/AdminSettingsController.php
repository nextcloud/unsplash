<?php

declare(strict_types=1);

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
     * AdminSettingsController constructor.
     *
     * @param    $appName
     * @param    IRequest $request
     * @param    SettingsService $settings
     */
    public function __construct(
        $appName,
        IRequest $request,
        private SettingsService $settings,
    )
    {
        parent::__construct($appName, $request);
    }

    /**
     * Update the app default settings
     *
     * @param string $key
     * @param        $value
     *
     * @return JSONResponse
     */
    public function set(string $key, bool|string|int|array|null $value): JSONResponse
    {
        // TODO: Refactor all the boolean handling (will need to be fixed in SettingsService at same time) and streamline all the code below
        if (isset($value) && is_string($value) && strtolower($value) === 'true') {
            $value = true;
        }
        if (isset($value) && is_string($value) && strtolower($value) === 'false') {
            $value = false;
        }

        if ($key === 'style/login') { // TODO: $value should be sanity checked too
            if ($value) {
                $this->settings->setServerStyleLoginEnabled(1);
            } else {
                $this->settings->setServerStyleLoginEnabled(0);
            }
        } else if ($key === 'style/dashboard') { // TODO: $value should be sanity checked too
            if ($value) {
                $this->settings->setServerStyleDashboardEnabled(1);
            } else {
                $this->settings->setServerStyleDashboardEnabled(0);
            }
        } else if ($key === 'provider/provider') {
            $this->settings->setImageProviderSanitized(filter_var($value, FILTER_SANITIZE_STRING));
            return $this->generateProviderResponse($value);
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
            return $this->generateProviderResponse("success");
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

    private function generateProviderResponse(string $value): JSONResponse
    {

        $cached = $this->settings->isCached();
        $provider = $this->settings->getSelectedImageProvider();
        $name = $provider->getName();
        $url = $provider->getCachedImageURL();
        return new JSONResponse([
            'status' => $value,
            'isCached' => $cached,
            'provider' => $name,
            'imageURL' => $url
        ]);
    }
}
