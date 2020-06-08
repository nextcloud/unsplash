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


namespace OCA\Unsplash\CssBuilder;

use OCA\Unsplash\Services\SettingsService;
use OCA\Theming;


$unsplashScript = get_included_files();
$unsplashScript = $unsplashScript[0]; //gets the current filepath
$unsplashScript = substr($unsplashScript, 0, -25);

$baseDir = substr($unsplashScript, 0, -14);


require_once $baseDir . 'lib/base.php';
//require $baseDir . 'apps/unsplash/lib/Settings/SettingsManager.php';

$app = new \OCA\Unsplash\AppInfo\Application();
$settings = $app->getContainer()->query(SettingsService::class);
$color = $settings->getInstanceColor();


$maincolor = str_split(str_replace("#", "", $color), 2);
$mainColorR = hexdec($maincolor[0]);
$mainColorG = hexdec($maincolor[1]);
$mainColorB = hexdec($maincolor[2]);

$greyscale=false;

if($greyscale){
	$mainColorR = hexdec(0);
	$mainColorG = hexdec(0);
	$mainColorB = hexdec(0);
}

$enableTint =  $settings->isTintAllowed();
$colorStrenght =  $settings->getColorStrength()/100;
$blurStrenght =  $settings->getBlurStrength();
$unsplashImagePath = $settings->headerbackgroundLink();

$blurEnabled = false;
if($blurStrenght>0){
	$blurEnabled = true;
}

header("Content-type: text/css; charset: UTF-8");
include $unsplashScript.'css/header.css';

?>
