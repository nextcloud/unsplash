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


namespace OCA\Unsplash\ProviderHandler;

use OCP\IConfig;

abstract class Provider {

    const SIZE_SMALL = 0;
    const SIZE_NORMAL = 1;
    const SIZE_HIGH = 2;
    const SIZE_ULTRA = 3;
    const SIZE_DEFAULT = Provider::SIZE_NORMAL;

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
     * // Please override this value for your own provider.
	 * @var string
	 */
	public string $DEFAULT_SEARCH="nature";

    public bool $ALLOW_CUSTOMIZING = true;


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
	 * This sets a custom search query if the provider supports this.
	 *
	 * @param string $term
	 */
	public function setCustomSearchTerms(string $term): void
    {
        if($this->ALLOW_CUSTOMIZING) {
            $this->config->setAppValue($this->appName, 'splash/provider/'.$this->providerName.'/searchterms', $term);
        }
	}

	/**
	 *
	 * This returns the custom searchterm
     * It is not filtered!
	 * @return string
	 */
	public function getCustomSearchterms(): string {
        if(!$this->ALLOW_CUSTOMIZING) {
            return "";
        }
        $term = $this->config->getAppValue($this->appName, 'splash/provider/'.$this->providerName.'/searchterms', $this->DEFAULT_SEARCH);
        if($term == "") {
            return $this->DEFAULT_SEARCH;
        }
		return $term;
	}

    /**
     *
     * This returns a single searchterm. It will be restricted to letters, and lowercase.
     * This filtering i s there to prevent url hijacking or malforming due to searchterms
     * @return string
     */
    public function getRandomSearchTerm(): string {
        $termarray = explode(",", $this->getCustomSearchterms());
        shuffle($termarray);

        $term = strtolower($termarray[0]);
        // only allow letters as searchterm
        $term = preg_replace('/[^a-z]/i','', $term);
        return $term;
    }

	/**
	 * Returns the providername
	 * @return string
	 */
	public function getName(): string
    {
		return $this->providerName;
	}

    /**
     * Returns if the provider is customizable
     * @return string
     */
    public function isCustomizable(): bool
    {
        return $this->ALLOW_CUSTOMIZING;
    }

	/*
	 * This should return all URLS which need to be whitelisted for csrf
	 */
	public abstract function getWhitelistResourceUrls();


	/*
	 * This should return a url to a random image
	 */
	public abstract function getRandomImageUrl($size = Provider::SIZE_DEFAULT);

	/*
	 * This should return a url to a random image filtered by $search
	 */
	public abstract function getRandomImageUrlBySearchTerm($search, $size = Provider::SIZE_DEFAULT);

}
