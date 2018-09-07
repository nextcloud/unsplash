<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCA\Unsplash\Cache\AbstractCache;
use OCA\Unsplash\Cache\AvatarCache;
use OCA\Unsplash\Cache\ImageCache;
use OCA\Unsplash\Db\Image;
use OCA\Unsplash\ImageProvider\ProviderInterface;
use OCA\Unsplash\ImageProvider\UnsplashProvider;
use OCP\AppFramework\IAppContainer;
use OCP\AppFramework\QueryException;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\Files\SimpleFS\ISimpleFile;

/**
 * Class ImageFetchingService
 *
 * @package OCA\Unsplash\Services
 */
class ImageFetchingService {

    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * @var AppSettingsService
     */
    protected $settingsService;

    /**
     * @var IAppContainer
     */
    protected $container;

    /**
     * @var ImageCache
     */
    protected $imageCache;

    /**
     * @var AvatarCache
     */
    protected $avatarCache;

    /**
     * ImageFetchingService constructor.
     *
     * @param ImageService       $imageService
     * @param AppSettingsService $settingsService
     * @param IAppContainer      $container
     * @param ImageCache         $imageCache
     * @param AvatarCache        $avatarCache
     */
    public function __construct(
        ImageService $imageService,
        AppSettingsService $settingsService,
        IAppContainer $container,
        ImageCache $imageCache,
        AvatarCache $avatarCache
    ) {
        $this->imageService    = $imageService;
        $this->settingsService = $settingsService;
        $this->container       = $container;
        $this->imageCache      = $imageCache;
        $this->avatarCache     = $avatarCache;
    }

    /**
     * Fetches new images with the given subject from the image provider.
     *
     * @param string $subject
     * @param int    $count
     *
     * @return array|Image[]
     * @throws QueryException
     * @throws NotFoundException
     * @throws NotPermittedException
     */
    public function fetchImages(string $subject, int $count) {
        $images = $this->getImageProvider()->fetchImages($subject, $count);

        foreach($images as $image) {
            $this->downloadImage($image->getSource(), $image->getUuid(), $this->imageCache);
            $this->downloadImage($image->getAvatarSource(), $image->getUuid(), $this->avatarCache);

            $this->imageService->save($image);
        }

        return $images;
    }

    /**
     * Get the currently active service to provide images
     *
     * @return ProviderInterface
     * @throws QueryException
     */
    public function getImageProvider(): ProviderInterface {
        $provider = $this->settingsService->getImageProvider();

        if($provider == 'unsplash') {
            return $this->container->query(UnsplashProvider::class);
        }

        return $this->container->query(UnsplashProvider::class);
    }

    /**
     * Removes an image from the storage
     *
     * @param Image $image
     */
    public function removeImage(Image $image) {
        $this->imageService->delete($image);

        try {
            $uuid = $image->getUuid();
            if($this->imageCache->has($uuid)) $this->imageCache->remove($uuid);
            if($this->avatarCache->has($uuid)) $this->avatarCache->remove($uuid);
        } catch(NotPermittedException $e) {
            \OC::$server->getLogger()->logException($e);
        }
    }

    /**
     *
     * Download an image from the internet
     *
     * @param $url
     * @param $filename
     *
     * @return \OCP\Files\SimpleFS\ISimpleFile
     * @throws NotFoundException
     * @throws NotPermittedException
     */
    protected function downloadImage($url, $filename, AbstractCache $cache): ISimpleFile {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curl);
        curl_close($curl);

        return $cache->put($filename, $data);
    }
}