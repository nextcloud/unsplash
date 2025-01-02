<?php
/**
 * @copyright Copyright (c) 2018, Felix Nüsse
 *
 * @author Felix Nüsse <felix.nuesse@t-online.de>
 *
 * @license GPL-v3.0
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Unsplash\ProviderHandler;

use OCA\Unsplash\Provider\BingWallpaperDaily;
use OCA\Unsplash\Provider\NextcloudImage;
use OCA\Unsplash\Provider\UnsplashAPI;
use OCA\Unsplash\Provider\WallhavenCC;
use OCA\Unsplash\Provider\WikimediaCommons;
use OCA\Unsplash\Provider\WikimediaCommonsDaily;
use OCP\Files\IAppData;
use OCP\IConfig;
use Psr\Log\LoggerInterface;

class ProviderDefinitions
{

    /** @var SettingsService */
    protected $settings;

    /** @var IConfig */
    protected $config;
    /**
     * @var string
     */
    protected $appName;
    /**
     * @var definitions This variable contains all available provider
     */
    protected $definitions = [];
    /** @var IAppData */
    private $appData;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ProviderDefinitions constructor.
     *
     * @param String $appName
     * @param LoggerInterface $logger
     * @param IConfig $settings
     * @param IAppData $appData
     */
    function __construct($appName, LoggerInterface $logger, IConfig $config, IAppData $appData)
    {

        $this->appName = $appName;
        $this->config = $config;
        $this->appData = $appData;
        $this->logger = $logger;

        $tmp = [];
        //add all provider to this array. The logic takes care of the rest.
        $tmp[] = new UnsplashAPI($this->appName, $logger, $this->config, $appData, "UnsplashAPI");
        $tmp[] = new NextcloudImage($this->appName, $logger, $this->config, $appData, "Nextcloud Image");
        $tmp[] = new WikimediaCommons($this->appName, $logger, $this->config, $appData, "WikimediaCommons");
        $tmp[] = new WikimediaCommonsDaily($this->appName, $logger, $this->config, $appData, "WikimediaCommons - Picture of the Day");
        $tmp[] = new WallhavenCC($this->appName, $logger, $this->config, $appData, "WallhavenCC");
        $tmp[] = new BingWallpaperDaily($this->appName, $logger, $this->config, $appData, "Bing Wallpaper - Picture of the Day");

        foreach ($tmp as &$value) {
            $this->definitions[$value->getName()] = $value;
        }
    }

    /***
     *  This returns the selected Provider
     *
     * @param $name String: Name of the Provider
     * @return Provider
     */
    function getProviderByName($name): Provider
    {

        if (!array_key_exists($name, $this->definitions)) {
            $this->logger->warning("Selected provider '{$name}' could not be found. Using Default. Please select an existing provider in the settings!");
            return new WikimediaCommonsDaily($this->appName, $this->logger, $this->config, $this->appData, "WikimediaCommons - Picture of the Day");
        }
        $provider = $this->definitions[$name];
        if ($provider == null) {
            return new WikimediaCommonsDaily($this->appName, $this->logger, $this->config, $this->appData, "WikimediaCommons - Picture of the Day");
        }
        return $this->definitions[$name];
    }

    /**
     * This returns all defined Provider
     *
     * @return Array with List of Providers
     */
    function getAllProviderNames()
    {
        $i = 0;
        $tmp = [];
        foreach ($this->definitions as &$value) {
            //array_push($tmp,$value->getName());
            $tmp[] = $value->getName();
            $i++;
        }
        return $tmp;
    }

}
