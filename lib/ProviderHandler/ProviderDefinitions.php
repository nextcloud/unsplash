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

use OCA\Unsplash\Provider\NextcloudImage;
use OCA\Unsplash\Provider\Unsplash;
use OCA\Unsplash\Provider\UnsplashAPI;
use OCA\Unsplash\Provider\WallhavenCC;
use OCA\Unsplash\Provider\WikimediaCommons;
use OCP\Files\IAppData;
use OCP\IConfig;

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

    /**
     * ProviderDefinitions constructor.
     *
     * @param String $appName
     * @param IConfig $settings
     * @param IAppData $appData
     */
    function __construct($appName, IConfig $config, IAppData $appData)
    {

        $this->appName = $appName;
        $this->config = $config;
        $this->appData = $appData;

        $tmp = [];
        //add all provider to this array. The logic takes care of the rest.
        $tmp[] = new Unsplash($this->appName, $this->config, $appData, "Unsplash");
        $tmp[] = new UnsplashAPI($this->appName, $this->config, $appData, "UnsplashAPI");
        $tmp[] = new NextcloudImage($this->appName, $this->config, $appData, "Nextcloud Image");
        $tmp[] = new WikimediaCommons($this->appName, $this->config, $appData, "WikimediaCommons");
        $tmp[] = new WallhavenCC($this->appName, $this->config, $appData, "WallhavenCC");

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

        $provider = $this->definitions[$name];
        if ($provider == null) {
            return new Unsplash($this->appName, $this->config, $this->appData, "Unsplash");
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
