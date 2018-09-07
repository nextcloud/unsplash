<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Model;

namespace OCA\Unsplash\ImageProvider;

use OCA\Theming\Util;
use OCA\Unsplash\Services\AppSettingsService;
use OCA\Unsplash\Services\ImageService;

/**
 * Class UnsplashProvider
 *
 * @package OCA\Unsplash\ImageProvider
 */
class UnsplashProvider implements ProviderInterface {

    /**
     * @var AppSettingsService
     */
    protected $settings;

    /**
     * @var Util
     */
    protected $util;

    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * UnsplashProvider constructor.
     *
     * @param AppSettingsService $settings
     * @param ImageService       $imageService
     * @param Util               $util
     */
    public function __construct(AppSettingsService $settings, ImageService $imageService, Util $util) {
        $this->settings     = $settings;
        $this->util         = $util;
        $this->imageService = $imageService;
    }

    /**
     * @inheritdoc
     */
    public function fetchImages(string $subject, int $amount): array {
        $imageInfo = $this->getImagesFromApi($subject, $amount);
        $subject   = $subject == '' ? 'any':$subject;

        $images = [];
        foreach($imageInfo as $info) {
            $isDark = $this->util->invertTextColor($info->color);

            $images[] = $this->imageService->create(
                $info->urls->regular,
                $info->user->profile_image->small,
                $info->links->html,
                $info->user->name,
                $subject,
                strval($info->description),
                'unsplash.com',
                $isDark
            );
        }

        return $images;
    }

    /**
     * @param string $query
     * @param int    $count
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
     * @inheritdoc
     */
    public function getSubjects(): array {
        return [
            'any',
            'architecture',
            'nature',
            'space',
            'drone-view',
            'landscape',
            'city',
            'science',
            'ocean',
            'forest',
            'mountain',
            'wallpaper'
        ];
    }
}