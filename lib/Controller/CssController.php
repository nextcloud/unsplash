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
use OCP\AppFramework\Utility\ITimeFactory;

/**
 * Class ProxyController
 *
 * @package OCA\Unsplash\Controller
 */
class CssController extends Controller {

    private $settings;

    /** @var ITimeFactory */
    private $timeFactory;


    /**
     * ProxyController constructor.
     *
     * @param                 $appName
     * @param IRequest        $request
     * @param SettingsService $settings
     * @param ITimeFactory $timeFactory
     */
    public function __construct($appName, IRequest $request, SettingsService $settings, ITimeFactory $timeFactory) {
        parent::__construct($appName, $request);
        $this->settings = $settings;
        $this->timeFactory = $timeFactory;
    }

    /**
     * Todo: check the flags below
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return DataDisplayResponse
     */
    public function dashboard(): DataDisplayResponse {
        $unsplashImagePath =  $this->settings->headerbackgroundLink();
        return $this->prepareResponse("#app-dashboard {background-image: url('" . $unsplashImagePath . "') !important;}");
    }

    /**
     * Todo: check the flags below
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return DataDisplayResponse
     */
    public function header(): DataDisplayResponse {
        $unsplashImagePath =  $this->settings->headerbackgroundLink();

        return $this->prepareResponse("#header {background-image: url('" . $unsplashImagePath . "') !important;}");
    }

    /**
     * Todo: check the flags below
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     * @PublicPage
     *
     * @return DataDisplayResponse
     */
    public function login(): DataDisplayResponse {
        $unsplashImagePath =  $this->settings->headerbackgroundLink();
        return $this->prepareResponse("body#body-login {background-image: url('" . $unsplashImagePath . "') !important;}");
    }

    /**
     * Creates the appropriate css response for the client.
     * Also:
     * see https://github.com/juliushaertl/theming_customcss/blob/master/lib/Controller/ThemingController.php
     *
     * @param String $css
     * @return DataDisplayResponse
     */
    private function prepareResponse(String $css): DataDisplayResponse {
        $response = new DataDisplayResponse($css, Http::STATUS_OK, ['Content-Type' => 'text/css']);
        $response->cacheFor(86400);
        $expires = new \DateTime();
        $expires->setTimestamp($this->timeFactory->getTime());
        $expires->add(new \DateInterval('PT24H'));
        $response->addHeader('Expires', $expires->format(\DateTime::RFC1123));
        $response->addHeader('Pragma', 'cache');
        return $response;
    }
}
