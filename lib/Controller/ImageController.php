<?php

declare(strict_types=1);

/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\Services\FetchService;
use OCA\Unsplash\Services\SettingsService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\FileDisplayResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\IJobList;
use OCP\Files\IAppData;
use OCP\IRequest;

/**
 * Class ImageController
 *
 * @package OCA\Unsplash\Controller
 */
class ImageController extends Controller
{
    /**
     * ImageController constructor.
     *
     * @param                 $appName
     * @param IRequest $request
     * @param SettingsService $settings
     * @param ITimeFactory $timeFactory
     */
    public function __construct(
        $appName,
        IRequest $request,
        private SettingsService $settings,
        private ITimeFactory $timeFactory,
        private IAppData $appData,
        private FetchService $service,
    )
    {
        parent::__construct($appName, $request);
    }

    /**
     * @NoCSRFRequired
     * @PublicPage
     * @NoSameSiteCookieRequired
     * @NoTwoFactorRequired
     *
     * @return FileDisplayResponse
     */
    public function get(): FileDisplayResponse
    {
        $providername = $this->settings->getSelectedImageProvider()->getName();
        $appdataFolder = $this->appData->getFolder($providername);
        $file = $appdataFolder->getFile("background.jpeg");

        return new FileDisplayResponse($file, Http::STATUS_OK, ['Content-Type' => 'image/jpeg']);
    }

    /**
     * @NoCSRFRequired
     * @PublicPage
     * @NoSameSiteCookieRequired
     * @NoTwoFactorRequired
     *
     * @return JSONResponse
     */
    public function getMetadata(): JSONResponse
    {
        $provider = $this->settings->getSelectedImageProvider();
        $metadata = $provider->getMetadata();

        return new JSONResponse(
            [
                'url' => $metadata->getImageUrl(), 
                'author' => $metadata->getImageAuthor(),
                'attribution' => $metadata->getAttributionUrl(),
                'description' => $metadata->getImageDescription(),
                'source' => $metadata->getSource()
            ]
        );
    }

    /**
     * Creates the appropriate css response for the client.
     * Also:
     * see https://github.com/juliushaertl/theming_customcss/blob/master/lib/Controller/ThemingController.php
     *
     * @param String $css
     * @return DataDisplayResponse
     */
    private function prepareResponse(string $css): DataDisplayResponse
    {
        return new DataDisplayResponse($css, Http::STATUS_OK, ['Content-Type' => 'text/css']);
    }

}
