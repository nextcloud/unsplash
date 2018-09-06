<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Model;

namespace OCA\Unsplash\ImageProvider;

use OCA\Theming\Util;
use OCA\Unspash\Db\ImageInfo;
use OCA\Unsplash\Cache\AbstractCache;
use OCA\Unsplash\Cache\AvatarCache;
use OCA\Unsplash\Cache\ImageCache;
use OCA\Unsplash\Services\ImageInfoService;
use OCA\Unsplash\Services\AppSettingsService;
use OCP\Files\SimpleFS\ISimpleFile;

class UnspashProvider implements ProviderInterface {

    /**
     * @var AppSettingsService
     */
    protected $settings;

    /**
     * @var ImageCache
     */
    protected $imageCache;
    /**
     * @var Util
     */
    private $util;
    /**
     * @var ImageInfoService
     */
    private $infoService;
    /**
     * @var AvatarCache
     */
    private $avatarCache;

    /**
     * UnspashProvider constructor.
     *
     * @param AppSettingsService $settings
     * @param ImageInfoService   $infoService
     * @param ImageCache         $imageCache
     * @param AvatarCache        $avatarCache
     * @param Util               $util
     */
    public function __construct(AppSettingsService $settings, ImageInfoService $infoService, ImageCache $imageCache, AvatarCache $avatarCache, Util $util) {
        $this->settings    = $settings;
        $this->imageCache  = $imageCache;
        $this->util        = $util;
        $this->infoService = $infoService;
        $this->avatarCache = $avatarCache;
    }

    /**
     * Returns a set of images matching the given query
     *
     * @param string $query
     * @param int    $count
     *
     * @return ImageInfo[]
     * @throws \OCP\Files\NotFoundException
     * @throws \OCP\Files\NotPermittedException
     */
    public function fetchImages(string $query, int $count): array {
        $imageInfo = $this->getImagesFromApi($query, $count);
        $subject     = $query == '' ? 'any':$query;

        $images = [];
        foreach($imageInfo as $info) {
            $isDark = $this->util->invertTextColor($info->color);

            $imageInfo = $this->infoService->create(
                $info->user->name,
                $info->description,
                $info->links->html,
                'unsplash.com',
                $subject,
                $isDark
            );

            $this->downloadImage($info->urls->regular, $imageInfo->getUuid(), $this->imageCache);
            $this->downloadImage($info->user->profile_image->small, $imageInfo->getUuid(), $this->avatarCache);

            $this->infoService->save($imageInfo);
        }

        return $images;
    }

    /**
     * @param int $count
     *
     * @return mixed
     */
    protected function getImagesFromApi(string $query, int $count) {
        $params = [
            'featured'    => 'true',
            'orientation' => 'landscape',
            'query'       => $query,
            'count'       => $count
        ];

        $curl = curl_init('https://api.unsplash.com/photos/random?'.http_build_query($params));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Client-ID '.$this->settings->getApiKey()
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    /**
     * @param $url
     * @param $filename
     *
     * @return \OCP\Files\SimpleFS\ISimpleFile
     * @throws \OCP\Files\NotFoundException
     * @throws \OCP\Files\NotPermittedException
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