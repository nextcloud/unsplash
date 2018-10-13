<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\ImageProvider;

use OCA\Theming\Util;
use OCA\Unsplash\Db\Image;
use OCA\Unsplash\Services\AppSettingsService;
use OCA\Unsplash\Services\ImageService;
use OCP\Security\IContentSecurityPolicyManager;

/**
 * Class UnsplashImageProvider
 *
 * @package OCA\Unsplash\ImageProvider
 */
class UnsplashImageProvider implements ImageProviderInterface {

    const DEFAULT_API_KEY = 'YTMxMzk5NDI5ZWFjY2ExYTIzMmFjNWNjNjAyNWVhY2QwYjIxODFjMzE0YTM5YzA1NzY5YTU2OWY1MzkwMDgyNA==';

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
     * UnsplashImageProvider constructor.
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
            $isDark = !$this->util->invertTextColor($info->color);

            $image= $this->imageService->create(
                $info->urls->small,
                $info->urls->raw.'?q=75&fm=jpg&w=1920&fit=max',
                $info->urls->full,
                $info->user->profile_image->medium,
                $info->urls->small,
                $info->urls->raw.'?q=75&fm=jpg&w=1920&fit=max',
                $info->urls->full,
                $info->user->profile_image->medium,
                $info->links->html.'?utm_source=nextcloud-integration&utm_medium=referral',
                $info->user->name,
                $subject,
                strval($info->description),
                $this->getName(),
                $isDark
            );
            $this->imageService->save($image);
            $images[] = $image;
        }

        return $images;
    }

    /**
     * Removes an image from the local storage
     *
     * @param Image $image The image to be removed
     */
    public function removeImage(Image $image): void {
        $this->imageService->delete($image);
    }

    /**
     * @inheritdoc
     */
    public function registerCsp(IContentSecurityPolicyManager $cspManager): void {
        $policy = new \OC\Security\CSP\ContentSecurityPolicy();
        $policy->addAllowedImageDomain('https://source.unsplash.com');
        $policy->addAllowedImageDomain('https://images.unsplash.com');
        $cspManager->addDefaultPolicy($policy);
    }

    /**
     * @inheritdoc
     */
    public function validateApiKey(string $apiKey): bool {
        try {
            $this->getImagesFromApi('', 1);
        } catch(\Exception $e) {
            return false;
        }

        return true;
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

    /**
     * @inheritdoc
     */
    public function getUrl(): string {
        return 'https://unsplash.com/';
    }

    /**
     * @inheritdoc
     */
    public function getLicenseUrl(): string {
        return 'https://unsplash.com/license';
    }

    /**
     * @inheritdoc
     */
    public function getName(): string {
        return 'Unsplash';
    }

    /**
     * @inheritdoc
     */
    public function getId(): string {
        return 'unsplash';
    }

    /**
     * @param string $query
     * @param int    $count
     *
     * @return mixed
     * @throws \Exception
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
            'Authorization: Client-ID '.$this->getApiKey()
        ]);

        $response = curl_exec($curl);

        $curl_error = curl_errno($curl);
        if($curl_error !== 0) {
            curl_close($curl);
            throw new \Exception('CURL Error while when using Unsplash API: '.$curl_error);
        }

        $http_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        if($http_code !== 200) {
            curl_close($curl);
            throw new \Exception('Unsplash API returned: '.$http_code);
        }
        curl_close($curl);

        return json_decode($response);
    }

    /**
     * @return string
     */
    protected function getApiKey(): string {
        $apiKey = $this->settings->getApiKey();

        if($apiKey !== null) return $apiKey;

        return base64_decode(self::DEFAULT_API_KEY);
    }
}