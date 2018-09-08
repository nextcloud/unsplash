<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

use OCA\Unsplash\Services\ImageFetchingService;

$textLicense = $l->t('under an open license free of charge');
/** @var OCA\Unsplash\ImageProvider\ProviderInterface $service */
$service = \OC::$server->query(ImageFetchingService::class)->getImageProvider();

$links = [
    "<a href=\"{$service->getLicenseUrl()}\" rel=\"noreferrer noopener\" target=\"_blank\">{$textLicense}</a>",
    "<a href=\"{$service->getUrl()}\" rel=\"noreferrer noopener\" target=\"_blank\">{$service->getName()}</a>"
];

print_unescaped($l->t('These images are provided %s by %s.', $links));