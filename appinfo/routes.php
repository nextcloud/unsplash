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
        ['name' => 'admin_settings#getCustomization', 'url' => '/settings/admin/getCustomization/{providername}', 'verb' => 'GET'],
        ['name' => 'personal_settings#set', 'url' => '/settings/personal/set', 'verb' => 'POST'],
        ['name' => 'css#login', 'url' => '/api/login.css', 'verb' => 'GET'],
        ['name' => 'css#header', 'url' => '/api/header.css', 'verb' => 'GET'],
        ['name' => 'css#dashboard', 'url' => '/api/dashboard.css', 'verb' => 'GET']
    ]
]);
