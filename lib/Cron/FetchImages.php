<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Cron;

use OC\BackgroundJob\TimedJob;
use OCA\Unsplash\ImageProvider\UnspashProvider;
use OCA\Unsplash\Services\AppSettingsService;

/**
 * Class FetchImages
 *
 * @package OCA\Unsplash\Cron
 */
class FetchImages extends TimedJob {

    /**
     * @var UnspashProvider
     */
    private $unspashProvider;

    /**
     * @var AppSettingsService
     */
    private $settingsService;

    /**
     * FetchImages constructor.
     *
     * @param UnspashProvider    $unspashProvider
     * @param AppSettingsService $settingsService
     */
    public function __construct(AppSettingsService $settingsService, UnspashProvider $unspashProvider) {

        //$this->setInterval(60*60);
        $this->setInterval(0);

        $this->unspashProvider = $unspashProvider;
        $this->settingsService = $settingsService;
    }

    /**
     * @param $argument
     *
     * @throws \OCP\Files\NotFoundException
     * @throws \OCP\Files\NotPermittedException
     */
    protected function run($argument) {
        $query = $this->settingsService->getImageSubject();

        $this->unspashProvider->fetchImages($query, 20);

        \OC::$server->getLogger()->error('test');
    }
}