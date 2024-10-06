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

use OC\AppFramework\Http\Request;
use OCA\Unsplash\ProviderHandler\Provider;

class WallhavenCC extends Provider
{

    /**
     * TODO : Properly get current nextcloud image, currently only the theming one is used.
     * @var string
     */
    public string $DEFAULT_SEARCH = "nature,colorful";
    public bool $ALLOW_CUSTOMIZING = true;
    public string $DEFAULT_METADATA_URL="https://wallhaven.cc/";

    public function getWhitelistResourceUrls(): array
    {
        return ["https://w.wallhaven.cc"];
    }

    public function getRandomImageUrl($size)
    {
        return $this->getRandomImageUrlBySearchTerm($this->getRandomSearchTerm(), $size);
    }

    public function getRandomImageUrlBySearchTerm($search, $size)
    {
        if(empty($search)) {
            $search = $this->DEFAULT_SEARCH;
        }

        $resolution = "1920x1080";
        switch ($size) {
            case Provider::SIZE_SMALL:
                $resolution = "1920x1080";
                break;
            case Provider::SIZE_NORMAL:
                $resolution = "2560x1440";
                break;
            case Provider::SIZE_HIGH:
            case Provider::SIZE_ULTRA:
                $resolution = "3840x2160";
                break;
        }


        $curl = curl_init('https://wallhaven.cc/api/v1/search?sorting=random&ratios=16x9,16x10&resolutions=' . $resolution . '&q=' . $search);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        $json = json_decode($response, true);

        try {
            $images = $json['data'][array_rand($json['data'])];
            return $images['path'];
        } catch (\Error $e) {
            $this->logger->alert("Your searchterms likely did not yield results for: ".$this->getName());
        }

        return (new NextcloudImage($this->appName, $this->logger, $this->config, $this->appData, "Nextcloud"))->getRandomImageUrl($size);
    }

    public function getCachedImageURL(): string
    {
        return $this->getRandomImageUrl("");
    }
}
