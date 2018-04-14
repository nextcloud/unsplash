<?php
/**
 * @copyright Copyright (c) 2017 Bjoern Schiessle <bjoern@schiessle.org>
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


namespace OCA\Unsplash\Settings;


use OCP\IConfig;

class SettingsManager {

	/** @var IConfig */
	private $config;

	private $headerbackgroundDefault = 'yes';
    private $headerbackgroundLinkDefault = 'https://source.unsplash.com/daily?nature';

	public function __construct(IConfig $config) {
		$this->config = $config;
	}

	/**
	 * Should the header when logged in or sharing also have a background image
	 *
	 * @return bool
	 */
	public function headerbackground() {
		$headerbackground = $this->config->getAppValue('unsplash', 'headerbackground', $this->headerbackgroundDefault);
		return $headerbackground === 'yes';
	}

    /**
     * Returns the URL to the custom Unsplash-path
     *
     * @return String
     */
    public function headerbackgroundLink() {
        $headerbackgroundLink = $this->config->getAppValue('unsplash', 'headerbackgroundlink', $this->headerbackgroundLinkDefault);

        if(isset($headerbackgroundLink) && $headerbackgroundLink!=""){
            return $headerbackgroundLink;
        }else{
            return $this->headerbackgroundLinkDefault;
        }

    }

}
