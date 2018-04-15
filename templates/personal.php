<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

use OCA\Unsplash\Settings\PersonalSettings;
use OCP\AppFramework\QueryException;

$app = new \OCA\Unsplash\AppInfo\Application();
try {
    /** @var PersonalSettings $controller */
    $controller = $app->getContainer()->query(PersonalSettings::class);
    return $controller->getForm()->render();
} catch(QueryException $e) {
    return $e->getMessage();
}

