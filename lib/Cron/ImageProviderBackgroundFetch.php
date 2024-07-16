<?php

namespace OCA\Unsplash\Cron;

use OCA\Unsplash\Services\FetchService;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;
use OCP\ILogger;


class ImageProviderBackgroundFetch extends TimedJob
{

    private FetchService $fetchService;
    private $logger;

    public function __construct(ITimeFactory $time, FetchService $service, ILogger $logger)
    {
        parent::__construct($time);
        $this->fetchService = $service;
        $this->logger = $logger;
        $logger->info("Initialize ImageProviderBackgroundFetch");

        // Run once a day
        $this->setInterval(24 * 3600);
    }

    protected function run($arguments)
    {
        $this->logger->info("ImageProviderBackgroundFetch: start fetch-service");
        $this->fetchService->fetch();
        $this->logger->info("ImageProviderBackgroundFetch: ended fetch-service");
    }

}
