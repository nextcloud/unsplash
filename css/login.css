<?php
    /**
     * @copyright Copyright (c) 2018 Felix Nüsse <felix.nuesse@t-online.de>
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

namespace OCA\Unsplash;

use OC;
use OCP\IConfig;
use OCA\Unsplash\Settings\SettingsManager;

$unsplashScript = get_included_files();
$unsplashScript = $unsplashScript[0]; //gets the current filepath
$unsplashScript=substr($unsplashScript, 0, -27);


require_once $unsplashScript . 'lib/base.php';
require $unsplashScript.'apps/unsplash/lib/Settings/SettingsManager.php';


$server = \OC::$server;
$config = OC::$server->getConfig();

$Settingsmanager=new SettingsManager($config);
$showHeader = $Settingsmanager->headerbackground();
$unsplashImagePath=$Settingsmanager->headerbackgroundLink();




header("Content-type: text/css; charset: UTF-8");
include $unsplashScript."config/config.php";


if($showHeader){
    include 'login_header.css';
}else{
     include 'login_background.css';
}





?>*/