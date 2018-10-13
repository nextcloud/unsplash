<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

$textLicense = $l->t('under an open license free of charge');
/** @var OCA\Unsplash\ImageProvider\ImageProviderInterface $service */
$service = \OC::$server->query(\OCA\Unsplash\ImageProvider\ImageProviderInterface::class);

$links = [
    "<a href=\"{$service->getLicenseUrl()}\" rel=\"noreferrer noopener\" target=\"_blank\">{$textLicense}</a>",
    "<a href=\"{$service->getUrl()}\" rel=\"noreferrer noopener\" target=\"_blank\">{$service->getName()}</a>"
];

print_unescaped($l->t('These images are provided %s by %s.', $links));