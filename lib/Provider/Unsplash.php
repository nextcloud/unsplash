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

class Unsplash extends Provider
{

    /**
     * @var string
     */
    public string $DEFAULT_SEARCH = "nature,nature";
    public bool $ALLOW_CUSTOMIZING = true;

    public function getWhitelistResourceUrls()
    {
        return ['https://source.unsplash.com', 'https://images.unsplash.com'];
    }

    public function getRandomImageUrl($size): string
    {
        return $this->getRandomImageUrlBySearchTerm($this->getRandomSearchTerm(), $size);
    }

    public function getRandomImageUrlBySearchTerm($search, $size): string
    {
        $url = "https://source.unsplash.com/featured/";
        switch ($size) {
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
        }
        return $url . "?" . $search;
    }
}
