<?php

/**
 * @copyright Copyright (c) 2019 Felix Nüsse <felix.nuesse@t-online.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Unsplash\Provider;
use OCA\Unsplash\ProviderHandler\Provider;

class NextcloudImage extends Provider{

    // Todo: Use URLGenerator. See AdminSettingsController
	private string $THEMING_URL="/index.php/apps/theming/img/background";
	public bool $ALLOW_CUSTOMIZING = false;

	public function getWhitelistResourceUrls(): array
    {
		return [];
	}

	public function getRandomImageUrl($size): string
    {
		return $this->THEMING_URL;
	}

	public function getRandomImageUrlBySearchTerm($search, $size): string
    {
		return $this->THEMING_URL;
	}
}
