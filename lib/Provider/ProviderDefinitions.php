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

namespace OCA\Unsplash\Provider;

use OCP\IConfig;

class ProviderDefinitions{

	/**
	 * @var SettingsService
	 */
	protected $settings;

	/**
	 * @var IConfig
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $appName;

	/**
	 * @var definitions This variable contains all available provider
	 */
	protected $definitions = [];


	/**
	 * ProviderDefinitions constructor.
	 *
	 * @param SettingsService $settings
	 */
	function __construct($appName, IConfig $config) {

		$this->appName = $appName;
		$this->config = $config;

		$tmp=[];
		//add all provider to this array. The logic takes care of the rest.
		array_push($tmp,new Unsplash($this->appName, $this->config,"Unsplash"));
		array_push($tmp,new NextcloudImage($this->appName, $this->config,"NextcloudImage"));
		array_push($tmp,new WikimediaCommons($this->appName, $this->config,"WikimediaCommonsDogs"));

		foreach ($tmp as &$value) {
			//$this->definitions = array_merge($this->definitions, array($value->getName()=>$value->getName()));
			$this->definitions[$value->getName()] = $value;
		}
	}

	/**
	 * This returns the selected Provider
	 *
	 * @return Name of the Provider
	 */
	function getProviderByName($name){
	    $provider = $this->definitions["Unsplash"];
        if (array_key_exists($name, $this->definitions)) {
            $provider = $this->definitions[$name];
        }
		return $provider;
	}

	/**
	 * This returns all defined Provider
	 *
	 * @return Array with Names of Provider
	 */
	function getAllProviderNames(){
		$i=0;
		$tmp=[];
		foreach ($this->definitions as &$value) {
			//array_push($tmp,$value->getName());
			array_push($tmp,$value->getName());
			$i++;
		}
		return $tmp;
	}

}