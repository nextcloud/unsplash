<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\Services\SettingsService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

/**
 * Class ProxyController
 *
 * @package OCA\Unsplash\Controller
 */
class ProxyController extends Controller {

    private $settings;
    /**
     * ProxyController constructor.
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
     * Get the image provided by the Imageprovider
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     * @PublicPage
     *
     * @return DataDisplayResponse
     */
    public function image(): DataDisplayResponse {
        $unsplashImagePath =  $this->settings->headerbackgroundLink();
        $img = $unsplashImagePath;
        $test = new DataDisplayResponse($this->getImage($img));
        $test->addHeader("Content-Type", "image/jpg");

        return $test;
    }

    /**
     * Get the url of the Imageprovider
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     * @PublicPage
     *
     * @return JSONResponse
     */
    public function url(): JSONResponse {
        $unsplashImagePath =  $this->settings->headerbackgroundLink();
        return new JSONResponse($unsplashImagePath);
    }

    function getImage($url){
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $resource = curl_exec($ch);
        curl_close ($ch);

        return $resource;
    }
}