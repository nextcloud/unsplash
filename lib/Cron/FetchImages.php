<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Cron;

use OC\BackgroundJob\TimedJob;
use OCA\Unsplash\Services\AppSettingsService;
use OCA\Unsplash\Services\ImageFetchingService;
use OCA\Unsplash\Services\ImageService;
use OCP\AppFramework\QueryException;

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
     * @var ImageFetchingService
     */
    protected $imageFetchingService;

    /**
     * FetchImages constructor.
     *
     * @param ImageService         $imageService
     * @param AppSettingsService   $settingsService
     * @param ImageFetchingService $imageFetchingService
     */
    public function __construct(
        ImageService $imageService,
        AppSettingsService $settingsService,
        ImageFetchingService $imageFetchingService
    ) {

        //$this->setInterval(60*60);
        $this->setInterval(0);

        $this->settingsService      = $settingsService;
        $this->imageFetchingService = $imageFetchingService;
        $this->imageService         = $imageService;
    }

    /**
     * Updated the entire image library
     *
     * @param $argument
     *
     * @throws QueryException
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
            $subjects = $this->imageFetchingService->getImageProvider()->getSubjects();

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
     *
     * @throws \OCP\AppFramework\QueryException
     * @throws \OCP\Files\NotFoundException
     * @throws \OCP\Files\NotPermittedException
     */
    protected function updateImagesBySubject(string $subject) {
        $oldImages = $this->imageService->findAllBySubject($subject);

        $amount = $this->settingsService->getImageAmount();
        $this->imageFetchingService->fetchImages($subject, $amount);

        foreach($oldImages as $image) {
            $this->imageFetchingService->removeImage($image);
        }
    }
}