<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCP\IConfig;
use Psr\Log\LoggerInterface;

/**
 * Class FetchService
 *
 * @package OCA\Unsplash\Services
 */
class FetchService
{

    /**
     * @var SettingsService
     */
    protected $settings;

    /**
     * FetchService constructor.
     *
     */
    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Fetch the currently selected provider image
     */
    public function fetch(): void
    {
        $provider = $this->settings->getSelectedImageProvider();
        if ($provider->isCached()) {
            $provider->fetchCached();
        }
    }

}
