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
        $imagePath =  $this->settings->headerbackgroundLink();

        $css = "#app-dashboard { background-image: url('$imagePath') !important;";
        $css.= $this->getTintStyle($imagePath);
        $css.= $this->getBlurStyle();
        $css .= "}";
        return $this->prepareResponse($css);
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
        $imagePath =  $this->settings->headerbackgroundLink();

        $css = "#header {background-image: url('" . $imagePath . "') !important;";
        $css.= $this->getTintStyle($imagePath);
        $css.= $this->getBlurStyle();
        $css .= "}";
        return $this->prepareResponse($css);
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
        $imagePath =  $this->settings->headerbackgroundLink();

        $css = "body#body-login {background-image: url('" . $imagePath . "') !important;";
        $css.= $this->getTintStyle($imagePath);
        $css.= $this->getBlurStyle();
        $css .= "}";
        return $this->prepareResponse($css);
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
        // $response->cacheFor(86400);
        $expires = new \DateTime();
        $expires->setTimestamp($this->timeFactory->getTime());
        $expires->add(new \DateInterval('PT24H'));
        //$response->addHeader('Expires', $expires->format(\DateTime::RFC1123));
        //$response->addHeader('Pragma', 'cache');
        return $response;
    }


    private function getBlurStyle(): string {
        $css = "";
        $blurStrenght =  $this->settings->getBlurStrength();
        $blurStrenghtpx =  $this->settings->getBlurStrength()."px";
        $blurEnabled =  $blurStrenght > 0;

        if($blurEnabled == 1){
            $css .= "backdrop-filter: blur($blurStrenghtpx);";
        }
        return $css;
    }

    private function getTintStyle(string $imagePath): string {
        $css = "";
        $tintEnabled =  $this->settings->isTintEnabled();
        if($tintEnabled == 1){
            $tintColor =  $this->settings->getInstanceColor();
            list($r, $g, $b) = sscanf($tintColor, "#%02x%02x%02x");
            $colorStrenght =  $this->settings->getColorStrength()/100;
            $css .= "background-image: ";
            $css .= "linear-gradient(";
            $css .= "rgba($r, $g, $b, $colorStrenght),";
            $css .= "rgba($r, $g, $b, $colorStrenght)";
            $css .= "), ";
            $css .= "url('$imagePath')";
            $css .= "!important;";
            $css .= "background-blend-mode: hard-light;\n";
        }
        return $css;
    }
}
