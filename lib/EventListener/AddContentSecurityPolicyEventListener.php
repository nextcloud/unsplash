<?php

declare(strict_types=1);

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

class AddContentSecurityPolicyEventListener implements IEventListener
{
    /**
     * AddContentSecurityPolicyEventListener constructor.
     *
     * @param SettingsService $settingsService
     */
    public function __construct(
        private SettingsService $settings,
    )
    {
    }

    /**
     * @param Event $event
     */
    public function handle(Event $event): void
    {
        if (!($event instanceof AddContentSecurityPolicyEvent)) {
            return;
        }

        if ($this->settings->getUserStyleDashboardEnabled() || $this->settings->getServerStyleLoginEnabled()) {
            $policy = new ContentSecurityPolicy();

            $urls = $this->settings->getWhitelistingUrlsForSelectedProvider();
            foreach ($urls as &$value) {
                $policy->addAllowedImageDomain($value);
            }
            $event->addPolicy($policy);
        }
    }
}
