<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\AppInfo;

$application = new Application();
/** @var $this \OC\Route\CachingRouter */
$application->registerRoutes($this, [
    'routes'    => [
        ['name' => 'admin_settings#set', 'url' => '/settings/admin/set', 'verb' => 'POST'],
        ['name' => 'personal_settings#set', 'url' => '/settings/personal/set', 'verb' => 'POST'],
    ]
]);