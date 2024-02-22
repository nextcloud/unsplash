<?php

namespace OCA\Unsplash\Cron;

use OCA\Unsplash\Services\FetchService;
use OCP\BackgroundJob\TimedJob;
use OCP\AppFramework\Utility\ITimeFactory;
use \OCP\ILogger;


class ImageProviderBackgroundFetch extends TimedJob {

    private FetchService $fetchService;
    private $logger;

    public function __construct(ITimeFactory $time, FetchService $service, ILogger $logger) {
        parent::__construct($time);
        $this->fetchService = $service;
        $this->logger = $logger;
        $this->logger->error("init", array('extra_context' => 'my extra context'));

        // Run once a day
        $this->setInterval(24*3600);
    }

    protected function run($arguments) {
        $this->logger->error("preRun", array('extra_context' => 'my extra context'));
        $this->fetchService->fetch();
        $this->logger->error("postRun", array('extra_context' => 'my extra context'));
    }

}
