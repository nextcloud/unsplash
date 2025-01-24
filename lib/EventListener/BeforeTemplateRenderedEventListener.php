<?php

declare(strict_types=1);

/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\EventListener;

use OCA\Unsplash\Services\SettingsService;
use OCP\AppFramework\Http\Events\BeforeLoginTemplateRenderedEvent;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Util;

class BeforeTemplateRenderedEventListener implements IEventListener
{
    /**
     * BeforeTemplateRenderedEventListener constructor.
     *
     * @param SettingsService $settingsService
     * @param IRequest $request
     * @param IURLGenerator $urlGenerator
     * @param LoggerInterface $logger
     */
    public function __construct(
        private SettingsService $settingsService,
        private IRequest $request,
        private IURLGenerator $urlGenerator,
    )
    {
    }

    /**
     * @param Event $event
     */
    public function handle(Event $event): void
    {
        if (!$event instanceof BeforeTemplateRenderedEvent && !$event instanceof BeforeLoginTemplateRenderedEvent) {
            return;
        }

        $route = $this->request->getParam('_route');
        $serverstyleDash = $this->settingsService->getUserStyleDashboardEnabled();
        $serverstyleLogin = $this->settingsService->getServerStyleLoginEnabled();

        switch ($route) {
            case 'core.TwoFactorChallenge.showChallenge':
            case 'files_sharing.Share.authenticate':
            // Nextcloud <= 28
            case 'core.login.showLoginForm':
            // Nextcloud >= 29
            case 'core.login.showloginform':
            case 'files_sharing.Share.showAuthenticate':
                if ($serverstyleLogin) {
                    $this->addHeaderFor('login');
                    $this->addMetadata();
                }
                break;
            case 'files_sharing.Share.showShare':
                if ($serverstyleDash) {
                    $this->addHeaderFor('dashboard');
                }
                break;
            case 'dashboard.dashboard.index':
                if ($event->isLoggedIn() && $serverstyleDash) {
                    $this->addHeaderFor('dashboard');
                    $this->addMetadata();
                }
                break;
            default:
                if ($event->isLoggedIn()) {
                    if ($serverstyleDash) {
                        $this->addHeaderFor('dashboard');
                    }
                }
                break;
        }
    }

    /**
     * Create both links, for static and dynamic css.
     * @param String $target
     * @return void
     */
    private function addHeaderFor(string $target): void
    {
        $linkToCSS = $this->urlGenerator->linkToRouteAbsolute('unsplash.css.' . $target);

        Util::addHeader('link', [
            'rel' => 'stylesheet',
            'href' => $linkToCSS,
        ]);

        Util::addStyle('unsplash', $target . '_static');
    }

    /**
     * Insert links to metadata scripts and styles
     * @return void
     */
    private function addMetadata(): void
    {
        Util::addScript('unsplash', "metadata");
        Util::addStyle('unsplash', "metadata");
    }

}
