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

        [
            'name' => 'Image#background',
            'url' => '/images/background/{uuid}/{resolution}',
            'verb' => 'GET',
            'defaults' => ['resolution' => 'medium'],
            'requirements' => ['resolution' => 'small|medium|large']
        ],
        [
            'name' => 'Image#avatar',
            'url' => '/images/avatar/{uuid}',
            'verb' => 'GET',
        ],
    ]
]);