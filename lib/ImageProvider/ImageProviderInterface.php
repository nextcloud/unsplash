<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\ImageProvider;

use OCA\Unsplash\Db\Image;
use OCP\Security\IContentSecurityPolicyManager;

/**
 * Interface ImageProviderInterface
 *
 * @package OCA\Unsplash\ImageProvider
 */
interface ImageProviderInterface {

    /**
     * Get the internal id to identify the service
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Returns the user readable name of the service
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the url to the front page of the service
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Get the url to the license page of the service
     *
     * @return string
     */
    public function getLicenseUrl(): string;

    /**
     * Returns a set of possible subjects/search queries for the service.
     *
     * @return array An array of strings with at least one entry
     */
    public function getSubjects(): array;

    /**
     * Returns a set of images matching the given subject
     *
     * @param string $subject A string to use as search query
     * @param int    $amount  The amount of results expected
     *
     * @return Image[] An array of images matching the $subject
     */
    public function fetchImages(string $subject, int $amount): array;

    /**
     * Removes an image from the local storage
     *
     * @param Image $image The image to be removed
     */
    public function removeImage(Image $image): void;

    /**
     * Register a csp for the images if necessary
     *
     * @param IContentSecurityPolicyManager $cpsManager
     */
    public function registerCsp(IContentSecurityPolicyManager $cpsManager): void;

    /**
     * Checks if a given api key is valid for the service
     *
     * @param string $apiKey The api key
     *
     * @return bool
     */
    public function validateApiKey(string $apiKey): bool;
}