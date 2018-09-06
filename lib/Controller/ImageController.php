<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\Cache\AvatarCache;
use OCA\Unsplash\Cache\ImageCache;
use OCA\Unsplash\Services\ImageService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\FileDisplayResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

/**
 * Class ImageController
 *
 * @package OCA\Unsplash\Controller
 */
class ImageController extends Controller {
    /**
     * @var ImageCache
     */
    private $imageCache;
    /**
     * @var AvatarCache
     */
    private $avatarCache;

    /**
     * ImageController constructor.
     *
     * @param string      $appName
     * @param IRequest    $request
     * @param ImageCache  $imageCache
     * @param AvatarCache $avatarCache
     */
    public function __construct(string $appName, IRequest $request, ImageCache $imageCache, AvatarCache $avatarCache) {
        parent::__construct($appName, $request);
        $this->imageCache = $imageCache;
        $this->avatarCache = $avatarCache;
    }

    /**
     * @PublicPage
     * @NoAdminRequired
     * @NoCSRFRequired
     * @UseSession
     *
     * @param string $uuid
     *
     * @return FileDisplayResponse
     */
    public function background(string $uuid) {
        $image = $this->imageCache->get($uuid);

        return new FileDisplayResponse(
            $image,
            Http::STATUS_OK,
            ['Content-Type' => $image->getMimeType()]
        );
    }

    /**
     * @PublicPage
     * @NoAdminRequired
     * @NoCSRFRequired
     * @UseSession
     *
     * @param string $uuid
     *
     * @return FileDisplayResponse
     */
    public function avatar(string $uuid) {
        $avatar = $this->avatarCache->get($uuid);

        return new FileDisplayResponse(
            $avatar,
            Http::STATUS_OK,
            ['Content-Type' => $avatar->getMimeType()]
        );
    }
}