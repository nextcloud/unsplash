<?php

declare(strict_types=1);

/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Cron;

use OCA\Unsplash\Services\FetchService;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;
use Psr\Log\LoggerInterface;

class ImageProviderBackgroundFetch extends TimedJob
{
    public function __construct(
        ITimeFactory $time,
        private FetchService $fetchService,
        private LoggerInterface $logger,
    )
    {
        parent::__construct($time);
        $this->logger->info("Initialize ImageProviderBackgroundFetch");

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
