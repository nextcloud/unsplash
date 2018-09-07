<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Controller;

use OCA\Unsplash\Cache\AvatarCache;
use OCA\Unsplash\Cache\ImageCache;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\FileDisplayResponse;
use OCP\Files\SimpleFS\ISimpleFile;
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
    protected $imageCache;

    /**
     * @var AvatarCache
     */
    protected $avatarCache;

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
        $this->imageCache  = $imageCache;
        $this->avatarCache = $avatarCache;
    }

    /**
     *
     * Returns the image with the given uuid
     *
     * @PublicPage
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $uuid
     *
     * @return FileDisplayResponse
     */
    public function background(string $uuid): FileDisplayResponse {
        $image = $this->imageCache->get($uuid);

        return $this->getImageResponse($image);
    }

    /**
     * Returns the avatar image with the given uuid
     *
     * @PublicPage
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $uuid
     *
     * @return FileDisplayResponse
     */
    public function avatar(string $uuid): FileDisplayResponse {
        $avatar = $this->avatarCache->get($uuid);

        return $this->getImageResponse($avatar);
    }

    /**
     * @param ISimpleFile $file
     *
     * @return FileDisplayResponse
     */
    protected function getImageResponse(ISimpleFile $file): FileDisplayResponse {
        $expires = new \DateTime();
        $expires->setTimestamp(time() + 3600);

        $response = new FileDisplayResponse(
            $file,
            Http::STATUS_OK,
            [
                'Content-Type'  => $file->getMimeType(),
                'Cache-Control' => 'public, immutable, max-age=3600',
                'Expires'       => $expires->format(\DateTime::RFC2822),
                'Pragma'        => 'cache'
            ]
        );

        return $response;
    }
}