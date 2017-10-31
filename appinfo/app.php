<?php

OCP\Util::addscript('unsplash', 'script');

$manager = \OC::$server->getContentSecurityPolicyManager();
$policy->addAllowedImageDomain('https://source.unsplash.com');
$policy->addAllowedStyleDomain('\'self\'');
$policy->addAllowedStyleDomain('\'unsafe-inline\'');
$policy->addAllowedScriptDomain('\'self\'');
$manager->addDefaultPolicy($policy);
