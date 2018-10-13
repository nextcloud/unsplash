<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Cron;

use OC\BackgroundJob\TimedJob;
use OCA\Unsplash\ImageProvider\ImageProviderInterface;
use OCA\Unsplash\Services\AppSettingsService;
use OCA\Unsplash\Services\ImageService;

/**
 * Class FetchImages
 *
 * @package OCA\Unsplash\Cron
 */
class FetchImages extends TimedJob {

    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * @var AppSettingsService
     */
    protected $settingsService;

    /**
     * @var ImageProviderInterface
     */
    protected $imageProvider;

    /**
     * FetchImages constructor.
     *
     * @param ImageService           $imageService
     * @param AppSettingsService     $settingsService
     * @param ImageProviderInterface $imageProvider
     */
    public function __construct(
        ImageService $imageService,
        AppSettingsService $settingsService,
        ImageProviderInterface $imageProvider
    ) {

        $this->setInterval(3 * 60 * 60);

        $this->settingsService = $settingsService;
        $this->imageProvider   = $imageProvider;
        $this->imageService    = $imageService;
    }

    /**
     * Updated the entire image library
     *
     * @param $argument
     */
    protected function run($argument) {
        if(!$this->settingsService->allowUserSubjects()) {
            $subject = $this->settingsService->getImageSubject();

            try {
                $this->updateImagesBySubject($subject);
            } catch(\Throwable $e) {
                \OC::$server->getLogger()->logException($e);
            }
        } else {
            $subjects = $this->imageProvider->getSubjects();

            foreach($subjects as $subject) {
                try {
                    $this->updateImagesBySubject($subject);
                } catch(\Throwable $e) {
                    \OC::$server->getLogger()->logException($e);
                }
            }
        }
    }

    /**
     * Fetches new images for the given subject and removes all current ones
     *
     * @param string $subject
     */
    protected function updateImagesBySubject(string $subject) {
        $oldImages = $this->imageService->findAllBySubject($subject);

        $amount = $this->settingsService->getImageAmount();
        $this->imageProvider->fetchImages($subject, $amount);

        foreach($oldImages as $image) {
            $this->imageProvider->removeImage($image);
        }
    }
}