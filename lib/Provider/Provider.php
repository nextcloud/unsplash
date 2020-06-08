<?php
/**
 * @copyright Copyright (c) 2019 Felix Nüsse <felix.nuesse@t-online.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace OCA\Unsplash\Provider;

use OCP\IConfig;

abstract class Provider{

	/**
	 * @var IConfig
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $appName;

	/**
	 * @var string
	 */
	private $providerName;

	/**
	 * @var string
	 */
	public $DEFAULT_URL="redefine this value";

	const ALLOW_URL_CUSTOMIZING = true;


	/**
	 * Provider constructor.
	 *
	 * @param $appName
	 * @param IConfig $config
	 * @param $pName
	 */
	public function __construct( $appName, IConfig $config, $pName) {
		$this->config = $config;
		$this->appName = $appName;
		$this->providerName = $pName;
	}

	/**
	 * This sets a custom url if the provider allows this.
	 *
	 * @param string $url
	 */
	public function setCustomURL(string $url){
		$this->config->setAppValue($this->appName, 'splash/provider/'.$this->providerName.'/url', $url);
	}

	/**
	 *
	 * This either returns the default url, or the custom one, if one is set
	 * @return mixed
	 */
	public function getURL(){
		return $this->config->getAppValue($this->appName, 'splash/provider/'.$this->providerName.'/url', $this->DEFAULT_URL);
	}

	/**
	 * Returns the providername
	 * @return string
	 */
	public function getName(){
		return $this->providerName;
	}

	/*
	 * This should return all URLS which need to be whitelisted for csrf
	 */
	public abstract function getWhitelistResourceUrls();


	/*
	 * This should return a url to a random image
	 */
	public abstract function getRandomImageUrl();

	/*
	 * This should return a url to a random image filtered by searchterms
	 */
	public abstract function getRandomImageUrlBySearchTerm($termarray);

}