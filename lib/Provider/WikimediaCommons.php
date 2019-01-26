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
use OCA\Unsplash\Provider\Provider;
use OC\AppFramework\Http\Request;

class WikimediaCommons extends Provider{

	/**
	 * TODO : Properly get current nextcloud image, currently only the theming one is used.
	 * @var string
	 */
	public $DEFAULT_URL="damn";
	const ALLOW_URL_CUSTOMIZING = true;

	public function getWhitelistResourceUrls()
	{
		return ["https://upload.wikimedia.org"];
	}

	public function getRandomImageUrl()
	{
		return $this->getRandomImageUrlBySearchTerm(['nature']);
	}

	public function getRandomImageUrlBySearchTerm($termarray)
	{
		$curl = curl_init('https://commons.wikimedia.org/w/api.php?action=query&generator=images&prop=imageinfo&gimlimit=500&redirects=1&titles=Dog&iiprop=timestamp|user|userid|comment|canonicaltitle|url&format=json');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl);
		$json = json_decode($response, true);
		$images = $json['query']['pages'][array_rand($json['query']['pages'])];

		return $images['imageinfo'][0]['url'];
	}
}