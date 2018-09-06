<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Cache;

use OCP\Files\IAppData;
use OCP\Files\NotFoundException;
use OCP\Files\SimpleFS\ISimpleFile;
use OCP\Files\SimpleFS\ISimpleFolder;

/**
 * Class AbstractCache
 *
 * @package OCA\Unsplash\Services
 */
abstract class AbstractCache {

    const CACHE_NAME = 'defaultCache';

    /**
     * @var IAppData
     */
    protected $appData;

    /**
     * AbstractCache constructor.
     *
     * @param IAppData $appData
     */
    public function __construct(IAppData $appData) {
        $this->appData = $appData;
    }

    /**
     * Returns the cache folder
     *
     * @return ISimpleFolder
     * @throws \Exception
     * @throws \OCP\Files\NotPermittedException
     */
    public function getCache(): ISimpleFolder {
        try {
            return $this->appData->getFolder(static::CACHE_NAME);
        } catch(NotFoundException $e) {
            return $this->appData->newFolder(static::CACHE_NAME);
        }
    }

    /**
     * Deletes the cache folder
     *
     * @throws \OCP\Files\NotPermittedException
     */
    public function clear() {
        $this->getCache()->delete();
    }

    /**
     * @return array
     * @throws \OCP\Files\NotPermittedException
     * @deprecated Dont't get image ids from here
     */
    public function list(): array {
        $fileCache   = $this->getCache();
        $cachedFiles = $fileCache->getDirectoryListing();

        $keys = [];
        foreach($cachedFiles as $file) {
            $keys[] = explode('.', $file->getName(), 2)[0];
        }

        return $keys;
    }

    /**
     * Returns whether or not the cache has a file
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool {
        try {
            return $this->getCache()->fileExists($this->getRealKey($key));
        } catch(\Throwable $e) {
            return false;
        }
    }

    /**
     * @param string $key
     *
     * @return null|ISimpleFile
     */
    public function get(string $key) {
        try {
            $cache = $this->getCache();
            $key   = $this->getRealKey($key);

            if($cache->fileExists($key)) return $cache->getFile($key);
        } catch(\Throwable $e) {
            return null;
        }

        return null;
    }

    /**
     * Put new content in the cache
     *
     * @param string $key
     * @param string $content
     *
     * @return ISimpleFile
     * @throws NotFoundException
     * @throws \OCP\Files\NotPermittedException
     */
    public function put(string $key, $content) {
        $cache = $this->getCache();
        $key   = $this->getRealKey($key);

        if($cache->fileExists($key)) {
            $fileModel = $cache->getFile($key);
        } else {
            $fileModel = $cache->newFile($key);
        }

        $fileModel->putContent($content);

        return $fileModel;
    }

    /**
     * @param string $key
     *
     * @throws \OCP\Files\NotPermittedException
     */
    public function remove(string $key) {
        $this->get($this->getRealKey($key))->delete();
    }

    abstract function getRealKey($key);
}