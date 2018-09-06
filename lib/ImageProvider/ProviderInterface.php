<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\ImageProvider;

use OCA\Unspash\Db\ImageInfo;
use OCA\Unsplash\Model\Image;

/**
 * Interface ProviderInterface
 *
 * @package OCA\Unsplash\ImageProvider
 */
interface ProviderInterface {

    /**
     * Returns a set of images matching the given query
     *
     * @param string $query
     * @param int    $count
     *
     * @return ImageInfo[]
     */
    public function fetchImages(string $query, int $count): array;
}