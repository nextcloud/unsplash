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
use OCP\Files\GenericFileException;
use OCP\Files\NotFoundException;

class UnsplashAPI extends Provider
{

    /**
     * @var string
     */
    public string $DEFAULT_SEARCH = "nature,colorful";
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
        $appdataFolder = $this->getImageFolder();

        try {
            $file = $appdataFolder->getFile("source.json");
            $json = $file->getContent();
        } catch (GenericFileException | NotFoundException $ex) {
            $this->logger->warning("Metadata file was not found. Refetching Image!");
            $this->fetchCached();
            $json = $appdataFolder->getFile("source.json")->getContent();
        }

        if($json === '') {
            $this->logger->warning("Unsplash API: could not decode source json!");
            return new ProviderMetadata("", "", "", "", "Unsplash");
        }

        $data = json_decode($json);

        if(isset($data->errors)) {
            $this->logger->warning("Unsplash API: " . $data->errors[0]);
            return new ProviderMetadata("", "", "", "", "Unsplash");
        }

        $url = $data[0]->urls->raw;
        $urlAttribution = $data[0]->links->html;
        $description = $data[0]->description;
        $author = $data[0]->user->name;
        return new ProviderMetadata($url, $urlAttribution, $description, $author, "Unsplash");
    }

    public function fetchCached()
    {
        $appdataFolder = $this->getImageFolder();
        $host = $this->getRandomImageUrl(Provider::SIZE_SMALL);
        $result = $this->getData($host);

        //todo: this currently only supports unsplash.
        $metadata = $appdataFolder->newFile("source.json");
        $metadata->putContent($result);

        $metadata = $this->getMetadata($this->appData);
        $image = $this->getData($metadata->getImageUrl());

        $file = $appdataFolder->newFile("background.jpeg");
        $file->putContent($image);
    }

    public function deleteCached()
    {
        $appdataFolder = $this->getImageFolder();
        //$appdataFolder->getFile("source.json")->delete();
        //$appdataFolder->getFile("background.jpeg")->delete();
    }


    public function getRandomImageUrl($size): string
    {
        return $this->getRandomImageUrlBySearchTerm($this->getRandomSearchTerm(), $size);
    }

    public function getRandomImageUrlBySearchTerm($search, $size): string
    {
        if(empty($search)) {
            $search = $this->DEFAULT_SEARCH;
        }

        $token = $this->getToken();
        if($token === '' && $this->requiresAuth()) {
            // If the token is empty, return the default image.
            $this->logger->alert("Unsplash API: the provided token was blank!");
            return (new NextcloudImage($this->appName, $this->logger, $this->config, $this->appData, "Nextcloud"))->getRandomImageUrl($size);
        }

        $url = "https://api.unsplash.com/photos/random?client_id=" . $this->getToken() . "&count=1&query=" . $search;
        // Todo: Figure out if we can reintroduce sizes.
        return $url;
    }
}
