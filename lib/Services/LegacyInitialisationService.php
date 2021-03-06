<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Services;

use OC\Security\CSP\ContentSecurityPolicy;
use OCP\Security\IContentSecurityPolicyManager;
use OCP\Util;

/**
 * Class LegacyInitialisationService
 *
 * @package OCA\Unsplash\Services
 */
class LegacyInitialisationService {

    /**
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * @var IContentSecurityPolicyManager
     */
    protected $contentSecurityPolicyManager;

    /**
     * LegacyInitialisationService constructor.
     *
     * @param SettingsService               $settingsService
     * @param IContentSecurityPolicyManager $contentSecurityPolicyManager
     */
    public function __construct(SettingsService $settingsService, IContentSecurityPolicyManager $contentSecurityPolicyManager) {
        $this->settingsService = $settingsService;
        $this->contentSecurityPolicyManager = $contentSecurityPolicyManager;
    }

    /**
     *
     */
    public function initialize() {
        $this->registerStyleSheets();
        $this->registerCsp();
    }

    /**
     * Add the stylesheets
     */
    protected function registerStyleSheets() {
        if($this->settingsService->getUserStyleHeaderEnabled()) {
            Util::addStyle('unsplash', 'header');
        }
        if($this->settingsService->getServerStyleLoginEnabled()) {
            Util::addStyle('unsplash', 'login');
        }
    }

    /**
     * Allow Unsplash hosts in the csp
     */
    protected function registerCsp() {
        if($this->settingsService->getUserStyleHeaderEnabled() || $this->settingsService->getServerStyleLoginEnabled()) {
            $policy  = new ContentSecurityPolicy();
            $policy->addAllowedImageDomain('https://source.unsplash.com');
            $policy->addAllowedImageDomain('https://images.unsplash.com');
            $this->contentSecurityPolicyManager->addDefaultPolicy($policy);
        }
    }
}