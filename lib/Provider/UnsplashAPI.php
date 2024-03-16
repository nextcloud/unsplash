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

use OCA\Unsplash\ProviderHandler\Provider;
use OCA\Unsplash\ProviderHandler\ProviderMetadata;

class UnsplashAPI extends Provider
{

    /**
     * @var string
     */
    public string $DEFAULT_SEARCH = "nature,nature";
    public bool $ALLOW_CUSTOMIZING = true;
    public bool $REQUIRES_AUTH = true;
    public bool $IS_CACHED = true;

    public function getWhitelistResourceUrls()
    {
        return ['https://images.unsplash.com'];
    }

    public function getCachedImageURL(): string
    {
        return $this->getMetadata()->getImageUrl();
    }

    public function getMetadata(): ProviderMetadata
    {
        $appdataFolder = $this->getImageFolder($this->appData);
        $data = json_decode($appdataFolder->getFile("source.json")->getContent());
        $url = $data[0]->urls->raw;
        $urlAttribution = $data[0]->links->html;
        $description = $data[0]->description;
        $author = $data[0]->user->name;
        return new ProviderMetadata($url, $urlAttribution, $description, $author, "Unsplash");
    }

    /**
     */
    public function fetchCached()
    {
        $appdataFolder = $this->getImageFolder($this->appData);
        $host = $this->getRandomImageUrl(Provider::SIZE_SMALL);
        $result = $this->getData($host);


        //todo: this currently only supports unsplash.
        $metadata = $appdataFolder->newFile("source.json");
        $metadata->putContent($result);


        $metadata = $this->getMetadata($appData);
        $image = $this->getData($metadata->getImageUrl());

        $file = $appdataFolder->newFile("test.jpeg");
        $file->putContent($image);
    }

    public function getRandomImageUrl($size): string
    {
        return $this->getRandomImageUrlBySearchTerm($this->getRandomSearchTerm(), $size);
    }

    public function getRandomImageUrlBySearchTerm($search, $size): string
    {
        $url = "https://api.unsplash.com//photos/random?client_id=" . $this->getToken() . "&count=1&query=" . $search;
        /*switch ($size) {
            case Provider::SIZE_SMALL:
                $url .= "1920x1080";
                break;
            case Provider::SIZE_NORMAL:
                $url .= "2560x1440";
                break;
            case Provider::SIZE_HIGH:
            case Provider::SIZE_ULTRA:
                $url .= "3840x2160";
                break;
        }*/
        return $url;
    }
}
