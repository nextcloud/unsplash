<?php

declare(strict_types=1);

/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

return [
	'routes' => [
		// AdminSettingsController
		[
			'name' => 'admin_settings#set',
			'url' => '/settings/admin/set',
	   		'verb' => 'POST'
		],
		[
			'name' => 'admin_settings#getCustomization',
			'url' => '/settings/admin/getCustomization/{providername}',
			'verb' => 'GET'
		],
		// Personal settings (TODO: clean-up / currently unused?)
		[
			'name' => 'personal_settings#set', 
			'url' => '/settings/personal/set',
			'verb' => 'POST'
		],
		// CssController
		[
			'name' => 'css#login',
			'url' => '/api/login.css',
			'verb' => 'GET'
		],
		[
			'name' => 'css#dashboard',
			'url' => '/api/dashboard.css',
			'verb' => 'GET'
		],
		// ImageController
		[
			'name' => 'image#get',
			'url' => '/api/image',
			'verb' => 'GET'
		],
		[
			'name' => 'image#getMetadata',
			'url' => '/api/metadata',
			'verb' => 'GET'
   		],
	],
];
