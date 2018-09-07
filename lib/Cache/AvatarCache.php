<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Cache;

/**
 * Class AvatarCache
 *
 * @package OCA\Unsplash\Cache
 */
class AvatarCache extends AbstractCache {

    const CACHE_NAME = 'avatars';

    /**
     * @param $key
     *
     * @return string
     */
    function getRealKey($key): string {
        return "$key.jpg";
    }
}