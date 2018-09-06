<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCA\Unsplash\Db\ImageInfo;
use OCA\Unsplash\ImageProvider\UnspashProvider;

/**
 * Class RandomImageService
 *
 * @package OCA\Unsplash\Services
 */
class RandomImageService {

    /**
     * @var ImageInfoService
     */
    private $infoService;

    /**
     * @var AppSettingsService
     */
    private $settingsService;

    /**
     * @var UnspashProvider
     */
    private $unspashProvider;

    /**
     * RandomImageService constructor.
     *
     * @param ImageInfoService   $infoService
     * @param AppSettingsService $settingsService
     * @param UnspashProvider    $unspashProvider
     */
    public function __construct(ImageInfoService $infoService, AppSettingsService $settingsService, UnspashProvider $unspashProvider) {
        $this->infoService     = $infoService;
        $this->settingsService = $settingsService;
        $this->unspashProvider = $unspashProvider;
    }

    /**
     * @return ImageInfo
     * @throws \OCP\Files\NotFoundException
     * @throws \OCP\Files\NotPermittedException
     */
    public function getRandomImage() {
        $query = $this->settingsService->getImageSubject();

        $images      = $this->infoService->findAllBySubject($query);
        $imageAmount = count($images);

        if($imageAmount === 0) {
            return $this->unspashProvider->fetchImages($query, 2)[0];
        }

        $random = rand(0, $imageAmount - 1);

        return $images[ $random ];
    }
}