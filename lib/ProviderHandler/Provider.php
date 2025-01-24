<?php

declare(strict_types=1);

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

use OCP\Files\IAppData;
use OCP\IConfig;
use Psr\Log\LoggerInterface;
use OCP\Files\NotFoundException;

abstract class Provider
{

    const SIZE_SMALL = 0;
    const SIZE_NORMAL = 1;
    const SIZE_HIGH = 2;
    const SIZE_ULTRA = 3;
    /**
     * // Please override this value for your own provider.
     * @var string
     */
    public string $DEFAULT_SEARCH = "nature";
    public bool $ALLOW_CUSTOMIZING = true;
    public bool $REQUIRES_AUTH = false;
    public string $DEFAULT_TOKEN = "";
    public bool $IS_CACHED = false;
    public string $CACHED_URL = "/index.php/apps/unsplash/api/image";
    public string $DEFAULT_METADATA_URL="";

    /**
     * Provider constructor.
     *
     * @param $appName
     * @param LoggerInterface $logger
     * @param IConfig $config
     * @param $pName
     */
    public function __construct(
        protected string $appName,
        protected LoggerInterface $logger,
        protected IConfig $config,
        protected IAppData $appData,
        protected string $providerName,
    )
    {
    }

    /**
     * This sets a custom search query if the provider supports this.
     *
     * @param string $term
     */
    public function setCustomSearchTerms(string $term): void
    {
        if ($this->ALLOW_CUSTOMIZING) {
            $this->config->setAppValue($this->appName, 'splash/provider/' . $this->providerName . '/searchterms', $term);
        }
    }

    /**
     *
     * This returns a single searchterm. It will be restricted to letters, and lowercase.
     * This filtering i s there to prevent url hijacking or malforming due to searchterms
     * @return string
     */
    public function getRandomSearchTerm(): string
    {
        $termarray = explode(",", $this->getCustomSearchterms());
        shuffle($termarray);

        $term = strtolower($termarray[0]);
        // only allow letters as searchterm
        $term = preg_replace('/[^a-z-]/i','', $term);
        return $term;
    }

    /**
     *
     * This returns the custom searchterm
     * It is not filtered!
     * @return string
     */
    public function getCustomSearchterms(): string
    {
        if (!$this->ALLOW_CUSTOMIZING) {
            return "";
        }
        $term = $this->config->getAppValue($this->appName, 'splash/provider/' . $this->providerName . '/searchterms', $this->DEFAULT_SEARCH);
        if ($term == "") {
            return $this->DEFAULT_SEARCH;
        }
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

    /**
     * Returns if the provider requires an api token
     * @return string
     */
    public function requiresAuth(): bool
    {
        return $this->REQUIRES_AUTH;
    }

    /**
     *
     * This returns the token.
     * It either returns the default or the stored token.
     *
     * @return string
     */
    public function getToken(): string
    {
        if (!$this->REQUIRES_AUTH) {
            return "";
        }
        $token = $this->config->getAppValue($this->appName, 'splash/provider/' . $this->providerName . '/token', $this->DEFAULT_TOKEN);
        return $token;
    }

    public function isCached(): bool
    {
        return $this->IS_CACHED;
    }

    /**
     */
    public function getCachedImageURL(): string
    {
        return $this->CACHED_URL;
    }

    /**
     */
    public function getMetadata(): ProviderMetadata
    {
        return new ProviderMetadata($this->DEFAULT_METADATA_URL, $this->DEFAULT_METADATA_URL, "", "", $this->providerName);
    }

    /**
     * fetches a background to be cached
     */
    public function fetchCached()
    {

    }

    /**
     * Deletes the currently cached background
     */
    public function deleteCached()
    {

    }

    /*
     * This should return all URLS which need to be whitelisted for csrf
     */
    public abstract function getWhitelistResourceUrls();


    /*
     * This should return a url to a random image
     */
    public abstract function getRandomImageUrl($size);

    /*
     * This should return a url to a random image filtered by $search
     */
    public abstract function getRandomImageUrlBySearchTerm($search, $size);


    /**
     *
     * This doesnt really belong here. I should create a utils class or something like it
     * @param $host
     * @return bool|string
     */
    protected function getData($host)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        if ($this->config->getSystemValueBool('debug', false)) {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    /**
     * This doesnt really belong here. I should create a utils class or something like it
     * @throws NotPermittedException
     */
    protected function getImageFolder()
    {
        try {
            $rootFolder = $this->appData->getFolder($this->providerName);
        } catch (NotFoundException $e) {
            $rootFolder = $this->appData->newFolder($this->providerName);
        }
        return $rootFolder;
    }

}
