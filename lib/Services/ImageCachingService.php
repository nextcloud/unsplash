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
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\IURLGenerator;

/**
 * Class ImageCachingService
 *
 * @package OCA\Unsplash\Services
 */
class ImageCachingService {

    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * @var ImageCache
     */
    protected $imageCache;

    /**
     * @var AvatarCache
     */
    protected $avatarCache;

    /**
     * @var IURLGenerator
     */
    protected $urlGenerator;

    /**
     * ImageCachingService constructor.
     *
     * @param ImageCache    $imageCache
     * @param AvatarCache   $avatarCache
     * @param ImageService  $imageService
     * @param IURLGenerator $urlGenerator
     */
    public function __construct(
        ImageCache $imageCache,
        AvatarCache $avatarCache,
        ImageService $imageService,
        IURLGenerator $urlGenerator
    ) {
        $this->imageService = $imageService;
        $this->imageCache   = $imageCache;
        $this->avatarCache  = $avatarCache;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Fetches new images with the given subject from the image provider.
     *
     * @param Image $image
     *
     * @return void
     * @throws NotFoundException
     * @throws NotPermittedException
     */
    public function cacheImage(Image $image): void {
        $uuid = $image->getUuid();
        $this->downloadImage($image->getSourceSmall(), $uuid.'-small', $this->imageCache);
        $this->downloadImage($image->getSourceMedium(), $uuid.'-medium', $this->imageCache);
        $this->downloadImage($image->getSourceLarge(), $uuid.'-large', $this->imageCache);
        $this->downloadImage($image->getAvatarSource(), $uuid, $this->avatarCache);

        $mediumUrl = $this->urlGenerator->linkToRoute('unsplash.Image.background', ['uuid' => $uuid]);
        $smallUrl  = $this->urlGenerator->linkToRoute('unsplash.Image.background', ['uuid' => $uuid, 'resolution' => 'small']);
        $largeUrl  = $this->urlGenerator->linkToRoute('unsplash.Image.background', ['uuid' => $uuid, 'resolution' => 'large']);
        $avatarUrl = $this->urlGenerator->linkToRoute('unsplash.Image.avatar', ['uuid' => $uuid]);

        $image->setUrlSmall($smallUrl);
        $image->setUrlMedium($mediumUrl);
        $image->setUrlLarge($largeUrl);
        $image->setAvatarUrl($avatarUrl);
        $this->imageService->save($image);
    }

    /**
     * Removes an image from the storage
     *
     * @param Image $image
     */
    public function removeImage(Image $image): void {
        try {
            $uuid = $image->getUuid();
            if($this->imageCache->has($uuid.'-small')) $this->imageCache->remove($uuid.'-small');
            if($this->imageCache->has($uuid.'-medium')) $this->imageCache->remove($uuid.'-medium');
            if($this->imageCache->has($uuid.'-large')) $this->imageCache->remove($uuid.'-large');
            if($this->avatarCache->has($uuid)) $this->avatarCache->remove($uuid);
        } catch(NotPermittedException $e) {
            \OC::$server->getLogger()->logException($e);
        }
    }

    /**
     *
     * Download an image from the internet
     *
     * @param               $url
     * @param               $filename
     * @param AbstractCache $cache
     *
     * @throws NotFoundException
     * @throws NotPermittedException
     */
    protected function downloadImage($url, $filename, AbstractCache $cache): void {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curl);
        curl_close($curl);

        $cache->put($filename, $data);
    }
}