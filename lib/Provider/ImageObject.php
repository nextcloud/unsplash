<?php
/**
 * @copyright Copyright (c) 2018, Felix Nüsse
 *
 * @author Felix Nüsse <felix.nuesse@t-online.de>
 *
 * @license GPL-v3.0
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

class ImageObject{

	protected $url;
	protected $description;
	protected $author;

	/**
	 * PersonalSettingsController constructor.
	 *
	 * @param                 $url
	 * @param IRequest        $description
	 * @param SettingsService $author
	 */
	public function __construct($imageUrl, $imageDescription, $imageAuthor) {

		$this->url = $imageUrl;
		$this->description = $imageDescription;
		$this->author = $imageAuthor;
	}


	/**
	 * Get the url of this image
	 * @return String url
	 */
	public function getImageUrl(){
		return $this->url;
	}

	/**
	 * Get the description of this image
	 * @return String url
	 */
	public function getImageDescription(){
		return $this->description;
	}

	/**
	 * Get the author of this image
	 * @return String url
	 */
	public function getImageAuthor(){
		return $this->author;
	}

}