<?php

OCP\Util::addStyle('unsplash', 'login');

$manager = \OC::$server->getContentSecurityPolicyManager();
$policy = new \OC\Security\CSP\ContentSecurityPolicy();
$policy->addAllowedImageDomain('https://source.unsplash.com');
$policy->addAllowedImageDomain('https://images.unsplash.com');
$manager->addDefaultPolicy($policy);
