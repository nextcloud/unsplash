<?php

declare(strict_types=1);

/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\ProviderHandler\Provider;
use OCA\Unsplash\Services\SettingsService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\IRequest;

/**
 * Class CssController
 *
 * @package OCA\Unsplash\Controller
 */
class CssController extends Controller
{
    /**
     * CssController constructor.
     *
     * @param    $appName
     * @param    IRequest $request
     * @param    SettingsService $settings
     * @param    ITimeFactory $timeFactory
     */
    public function __construct(
        $appName,
        IRequest $request,
        private SettingsService $settings,
        private ITimeFactory $timeFactory,
    )
    {
        parent::__construct($appName, $request);
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return DataDisplayResponse
     */
    public function dashboard(): DataDisplayResponse
    {
        // we use the data-dasboard-background attribute to select the dashboard only when the user selected the default
        // one. This allows us to fully remove the dashboard setting, because the "standard" selection should
        // be identical to the login screen.
        return $this->prepareResponse($this->mediaQuery("body"));
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
        $response = new DataDisplayResponse($css, Http::STATUS_OK, ['Content-Type' => 'text/css']);
        // $response->cacheFor(86400);
        $expires = new \DateTime();
        $expires->setTimestamp($this->timeFactory->getTime());
        $expires->add(new \DateInterval('PT24H'));
        //$response->addHeader('Expires', $expires->format(\DateTime::RFC1123));
        //$response->addHeader('Pragma', 'cache');
        $response->addHeader('Content-Type', 'text/css');
        return $response;
    }

    /**
     * Create the full css media query with the appropriate inner css-tags.
     * @param $prefix
     * @return string
     */
    private function mediaQuery(string $prefix): string
    {
        $css_small = $this->innerCSS($prefix, Provider::SIZE_SMALL);
        $css_normal = $this->innerCSS($prefix, Provider::SIZE_NORMAL);
        $css_high = $this->innerCSS($prefix, Provider::SIZE_HIGH);

        $css = <<<EOT
            @media only screen and (max-width: 1920px) {
                $css_small
            }
            @media only screen and (min-width: 1921px) and (max-width: 2560px) {
                $css_normal
            }
            @media only screen and (min-width: 2561px) {
                $css_high
            }
        EOT;
        return $css;
    }

    /**
     * Create css for inner media queries, prefixed with the proper tags.
     * @param $prefix
     * @param $size
     * @return string
     */
    private function innerCSS($prefix, $size): string
    {
        $imagePath = $this->settings->headerbackgroundLink($size);
        $css = $prefix . " { background-image: url('$imagePath') !important;";
        $css .= $this->getTintStyle($imagePath);
        $css .= $this->getBlurStyle();
        $css .= "}";
        return $css;
    }

    private function getTintStyle(string $imagePath): string
    {
        $css = "";
        $tintEnabled = $this->settings->isTintEnabled();
        if ($tintEnabled == 1) {
            $tintColor = $this->settings->getInstanceColor();
            list($r, $g, $b) = sscanf($tintColor, "#%02x%02x%02x");
            $colorStrenght = $this->settings->getColorStrength() / 100;
            $css .= "background-color: #fff !important;";
            $css .= "background-image: ";
            $css .= "linear-gradient(";
            $css .= "rgba($r, $g, $b, $colorStrenght),";
            $css .= "rgba($r, $g, $b, $colorStrenght)";
            $css .= "), ";
            $css .= "url('$imagePath')";
            $css .= "!important;";
            $css .= "background-blend-mode: normal, multiply;";
        }
        return $css;
    }

    private function getBlurStyle(): string
    {
        $css = "";
        $blurStrenght = $this->settings->getBlurStrength();
        $blurStrenghtpx = $this->settings->getBlurStrength() . "px";
        $blurEnabled = $blurStrenght > 0;

        if ($blurEnabled == 1) {
            $css .= "backdrop-filter: blur($blurStrenghtpx);";
        }
        return $css;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return DataDisplayResponse
     */
    public function header(): DataDisplayResponse
    {
        return $this->prepareResponse($this->mediaQuery("body"));
    }

    /**
     * @NoCSRFRequired
     * @PublicPage
     * @NoSameSiteCookieRequired
     * @NoTwoFactorRequired
     *
     * @return DataDisplayResponse
     */
    public function login(): DataDisplayResponse
    {
        $css = $this->mediaQuery("body#body-login");

        $highVisibilityEnabled = $this->settings->isHighVisibilityLogin();
        if ($highVisibilityEnabled == 1) {
            $css .= $this->getHighVisibilityStyleLogin();
        }
        return $this->prepareResponse($css);
    }

    private function getHighVisibilityStyleLogin(): string
    {
        $css = "footer { min-height: 120px !important;}";
        $css .= ".legal {";
        //$css .= "top: 20px;";
        //$css .= "position: relative;";
        $css .= "background-color: var(--color-primary-element);";
        $css .= "color: var(--color-primary-text) !important;";
        $css .= "cursor: pointer;";
        $css .= "border-radius: 22px;";
        $css .= "padding: 15px;";
        $css .= "}";
        $css .= "p.info {padding: 15px;height: 80px;line-height: 3;}";
        return $css;
    }
}
