<?php

/**
 * @copyright Copyright (c) 2024 Bruce Truth | Bruce Mubangwa <bruce@broosaction.com>
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

class BingWallpaperDaily extends Provider
{
    /**
     * Indicates whether customization is allowed.
     * @var bool
     */
    public bool $ALLOW_CUSTOMIZING = false;

    /**
     * Indicates if images are cached.
     * @var bool
     */
    public bool $IS_CACHED = true;

    /**
     * Default metadata URL for Bing wallpapers.
     * @var string
     */
    public string $DEFAULT_METADATA_URL = 'https://www.bing.com';


    /**
     * Get URLs that are allowed for the Bing wallpaper.
     *
     * @return array
     */
    public function getWhitelistResourceUrls(): array
    {
        return ['https://www.bing.com'];
    }

    /**
     * Provide a cached image URL or the latest Bing daily wallpaper.
     *
     * @return string
     */
    public function getCachedImageURL(): string
    {
        return $this->getRandomImageUrl("");
    }

    public function getRandomImageUrl($size)
    {
        return $this->getRandomImageUrlBySearchTerm($this->getRandomSearchTerm(), $size);
    }

    public function getRandomImageUrlBySearchTerm($search, $size)
    {
        // Fetch the daily image JSON from Bing
        $bing_daily_image_json = file_get_contents('https://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=en-US');
        if ($bing_daily_image_json !== false) {
            $matches = json_decode($bing_daily_image_json);
            if (isset($matches->images[0]->url)) {
                // If unable to encode, return the image URL
                return 'https://www.bing.com' . $matches->images[0]->url;
            }
        }

        // Return default image if no Bing image is found
        return (new NextcloudImage($this->appName, $this->logger, $this->config, $this->appData, "Nextcloud"))->getRandomImageUrl($size);
    }
}