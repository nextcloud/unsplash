<?php

namespace OCA\Unsplash;

use OC;
use OCP\IConfig;


$unsplashScript = get_included_files();
$unsplashScript = $unsplashScript[0]; //gets the current filepath
$unsplashScript=substr($unsplashScript, strlen($_SERVER['DOCUMENT_ROOT']), -9);


\OCP\Util::addHeader(
    'link',
    [
        'rel'  => "stylesheet",
        'type' =>"text/css",
        'href' => $unsplashScript."apps/unsplash/css/login.php",
    ]
);


$manager = \OC::$server->getContentSecurityPolicyManager();
$policy = new \OC\Security\CSP\ContentSecurityPolicy();
$policy->addAllowedImageDomain('https://source.unsplash.com');
$policy->addAllowedImageDomain('https://images.unsplash.com');
$manager->addDefaultPolicy($policy);
