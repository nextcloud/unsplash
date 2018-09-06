<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OCA\Unsplash\Db\ImageInfo;
use OCA\Unsplash\Db\ImageInfoMapper;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\QueryException;

/**
 * Class ImageInfoService
 *
 * @package OCA\Unsplash\Services
 */
class ImageInfoService {

    /**
     * @var ImageInfoMapper
     */
    protected $infoMapper;

    /**
     * ImageInfoService constructor.
     *
     * @param ImageInfoMapper $imageInfoMapper
     */
    public function __construct(ImageInfoMapper $imageInfoMapper) {
        $this->infoMapper = $imageInfoMapper;
    }

    /**
     * @return string
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
     * @return ImageInfo[]
     */
    public function findAll(): array {
        return $this->infoMapper->findAll();
    }

    /**
     * @param string $subject
     *
     * @return ImageInfo[]
     */
    public function findAllBySubject(string $subject): array {
        return $this->infoMapper->findAllBySubject($subject);
    }

    /**
     * @param string $creator
     * @param string $description
     * @param string $link
     * @param string $provider
     * @param string $subject
     * @param bool   $isDark
     *
     * @return ImageInfo
     */
    public function create(string $creator, string $description, string $link, string $provider, string $subject, bool $isDark): ImageInfo {
        $model = new ImageInfo();
        $model ->setUuid($this->generateUuidV4());
        $model->setCreator($creator);
        $model->setDescription($description);
        $model->setLink($link);
        $model->setProvider($provider);
        $model->setSubject($subject);
        $model->setIsDark($isDark);

        return $model;
    }

    /**
     * @param ImageInfo $model
     *
     * @return ImageInfo
     */
    public function save(ImageInfo $model): ImageInfo {
        if(empty($model->getId())) {
            $saved = $this->infoMapper->insert($model);
        } else {
            $saved = $this->infoMapper->update($model);
        }

        /** @var $saved ImageInfo */
        return $saved;
    }
}