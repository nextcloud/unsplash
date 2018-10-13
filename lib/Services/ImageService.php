<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCA\Unsplash\Db\Image;
use OCA\Unsplash\Db\ImageMapper;

/**
 * Class ImageService
 *
 * @package OCA\Unsplash\Services
 */
class ImageService {

    /**
     * @var ImageMapper
     */
    protected $imageMapper;

    /**
     * ImageService constructor.
     *
     * @param ImageMapper $imageMapper
     */
    public function __construct(ImageMapper $imageMapper) {
        $this->imageMapper = $imageMapper;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateUuidV4(): string {
        return implode('-', [
            bin2hex(random_bytes(4)),
            bin2hex(random_bytes(2)),
            bin2hex(chr((ord(random_bytes(1)) & 0x0F) | 0x40)).bin2hex(random_bytes(1)),
            bin2hex(chr((ord(random_bytes(1)) & 0x3F) | 0x80)).bin2hex(random_bytes(1)),
            bin2hex(random_bytes(6))
        ]);
    }

    /**
     * @return Image[]
     */
    public function findAll(): array {
        return $this->imageMapper->findAll();
    }

    /**
     * @param string $subject
     *
     * @return Image[]
     */
    public function findAllBySubject(string $subject): array {
        return $this->imageMapper->findAllBySubject($subject);
    }

    /**
     * @param string $uuid
     *
     * @return Image
     * @throws \OCP\AppFramework\Db\DoesNotExistException
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
     */
    public function findByUuid(string $uuid) {
        return $this->imageMapper->findByUuid($uuid);
    }

    /**
     * @param string $sourceSmall
     * @param string $sourceMedium
     * @param string $sourceLarge
     * @param string $sourceAvatar
     * @param string $urlSmall
     * @param string $urlMedium
     * @param string $urlLarge
     * @param string $urlAvatar
     * @param string $link
     * @param string $creator
     * @param string $subject
     * @param string $description
     * @param string $provider
     * @param bool   $isDark
     *
     * @return Image
     * @throws \Exception
     */
    public function create(
        string $sourceSmall,
        string $sourceMedium,
        string $sourceLarge,
        string $sourceAvatar,
        string $urlSmall,
        string $urlMedium,
        string $urlLarge,
        string $urlAvatar,
        string $link,
        string $creator,
        string $subject,
        string $description,
        string $provider,
        bool $isDark
    ): Image {

        $uuid = $this->generateUuidV4();

        $model = new Image();
        $model->setUuid($uuid);

        $model->setUrlSmall($urlSmall);
        $model->setUrlMedium($urlMedium);
        $model->setUrlLarge($urlLarge);

        $model->setSourceSmall($sourceSmall);
        $model->setSourceMedium($sourceMedium);
        $model->setSourceLarge($sourceLarge);

        $model->setAvatarUrl($urlAvatar);
        $model->setAvatarSource($sourceAvatar);

        $model->setLink($link);
        $model->setCreator($creator);
        $model->setSubject($subject);
        $model->setDescription($description);

        $model->setProvider($provider);
        $model->setIsDark($isDark);

        return $model;
    }

    /**
     * @param Image $image
     *
     * @return Image
     */
    public function save(Image $image): Image {
        if(empty($image->getId())) {
            $saved = $this->imageMapper->insert($image);
        } else {
            $saved = $this->imageMapper->update($image);
        }

        /** @var $saved Image */
        return $saved;
    }

    /**
     * @param Image $image
     */
    public function delete(Image $image) {
        if(!empty($image->getId())) {
            $this->imageMapper->delete($image);
        }
    }
}