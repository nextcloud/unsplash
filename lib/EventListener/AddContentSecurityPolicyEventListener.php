<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\EventListener;

use OC\Security\CSP\ContentSecurityPolicy;
use OCA\Unsplash\Services\SettingsService;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

class AddContentSecurityPolicyEventListener implements IEventListener {
    /**
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * BeforeTemplateRenderedEventListener constructor.
     *
     * @param SettingsService $settingsService
     */
    public function __construct(SettingsService $settingsService) {
        $this->settingsService = $settingsService;
    }

    /**
     * @param Event $event
     */
    public function handle(Event $event): void {
        if(!($event instanceof AddContentSecurityPolicyEvent)) {
            return;
        }

        $settings = $this->settingsService;

        if($settings->getUserStyleDashboardEnabled() || $settings->getServerStyleLoginEnabled()) {
            $policy  = new ContentSecurityPolicy();

            $urls = $settings->getWhitelistingUrlsForSelectedProvider();
            foreach ($urls as &$value) {
                $policy->addAllowedImageDomain($value);
            }
            $event->addPolicy($policy);
        }
    }
}
