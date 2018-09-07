<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCA\Unsplash\Db\Image;
use OCP\ISession;

/**
 * Class UserImageService
 *
 * @package OCA\Unsplash\Services
 */
class UserImageService {

    const SESSION_KEY = 'unsplash.image';

    /**
     * @var ISession
     */
    protected $session;

    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * @var ImageFetchingService
     */
    protected $imageFetchingService;

    /**
     * @var UserSettingsService
     */
    protected $settingsService;

    /**
     * UserImageService constructor.
     *
     * @param ISession             $session
     * @param ImageService         $imageService
     * @param UserSettingsService  $settingsService
     * @param ImageFetchingService $imageFetchingService
     */
    public function __construct(
        ISession $session,
        ImageService $imageService,
        UserSettingsService $settingsService,
        ImageFetchingService $imageFetchingService
    ) {
        $this->session              = $session;
        $this->imageService         = $imageService;
        $this->settingsService      = $settingsService;
        $this->imageFetchingService = $imageFetchingService;
    }

    /**
     * Returns the current user image
     *
     * @param string $subject
     *
     * @return Image
     */
    public function getUserImage(string $subject) {
        $sessionImage = $this->getSessionImage();
        if($sessionImage != null) return $sessionImage;

        $randomImage = $this->getRandomImage($subject);
        if($randomImage != null) return $randomImage;

        return $this->getNewImage($subject);
    }

    /**
     * Returns the current session image
     * if the functionality is enabled.
     *
     * @return null|Image
     */
    protected function getSessionImage() {
        if(!$this->settingsService->imagePersistenceEnabled()) {
            return null;
        }

        if(!$this->session->exists(self::SESSION_KEY)) {
            return null;
        }

        $imageUuid = $this->session->get(self::SESSION_KEY);
        try {
            return $this->imageService->findByUuid($imageUuid);
        } catch(\Throwable $e) {
            return null;
        }
    }

    /**
     * Returns a random image from the storage
     * if there are images in the storage.
     *
     * @param string $subject
     *
     * @return null|Image
     */
    protected function getRandomImage(string $subject) {
        $images      = $this->imageService->findAllBySubject($subject);
        $imageAmount = count($images);

        if($imageAmount === 0) return null;

        $random = rand(0, $imageAmount - 1);
        $image  = $images[ $random ];
        $this->setSessionImage($image->getUuid());

        return $image;
    }

    /**
     * Fetches two image from the image provider
     * and returns the first one.
     *
     * @param string $subject
     *
     * @return null|Image
     */
    protected function getNewImage(string $subject) {
        try {
            $image = $this->imageFetchingService->fetchImages($subject, 2)[0];
            $this->setSessionImage($image->getUuid());

            return $image;
        } catch(\Throwable $e) {
            \OC::$server->getLogger()->logException($e);
        }

        return null;
    }

    /**
     * Stores the given image uuid in the session.
     *
     * @param string $uuid
     */
    protected function setSessionImage(string $uuid) {
        $this->session->set(self::SESSION_KEY, $uuid);
    }
}