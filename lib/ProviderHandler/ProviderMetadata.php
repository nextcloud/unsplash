<?php

namespace OCA\Unsplash\ProviderHandler;

use IRequest;
use SettingsService;

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
class ProviderMetadata
{

    protected $url;
    protected $attributionUrl;
    protected $description;
    protected $author;
    protected $source;

    /**
     * PersonalSettingsController constructor.
     *
     * @param                 $url
     * @param IRequest $description
     * @param SettingsService $author
     */
    public function __construct($imageUrl, $attributionUrl, $imageDescription, $imageAuthor, $imageSource)
    {
        $this->url = $imageUrl;
        $this->attributionUrl = $attributionUrl;
        $this->description = $imageDescription;
        $this->author = $imageAuthor;
        $this->source = $imageSource;
    }


    /**
     * Get the url of this image
     * @return String url
     */
    public function getImageUrl()
    {
        return $this->url;
    }

    /**
     * Get the description of this image
     * @return String url
     */
    public function getImageDescription()
    {
        return $this->description;
    }

    /**
     * Get the author of this image
     * @return String url
     */
    public function getImageAuthor()
    {
        return $this->author;
    }

    /**
     * Get the url to the profile of this image
     * @return String url
     */
    public function getAttributionUrl()
    {
        return $this->attributionUrl;
    }

    /**
     * Get the Imageprovider
     * @return String url
     */
    public function getSource()
    {
        return $this->source;
    }

}
