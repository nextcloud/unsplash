<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\EventListener;

use OCA\Unsplash\Services\SettingsService;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IRequest;
use OCP\IConfig;
use OCP\IURLGenerator;
use OCP\Util;

class BeforeTemplateRenderedEventListener implements IEventListener {

    /** @var SettingsService */
    protected $settingsService;
    /** @var IRequest */
    protected $request;
    /** @var IURLGenerator */
    private $urlGenerator;

    /**
     * BeforeTemplateRenderedEventListener constructor.
     *
     * @param SettingsService $settingsService
     * @param IRequest        $request
     */
    public function __construct(SettingsService $settingsService, IRequest $request, IURLGenerator $urlGenerator) {
        $this->settingsService = $settingsService;
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Event $event
     */
    public function handle(Event $event): void {
        if(!($event instanceof BeforeTemplateRenderedEvent)) {
            return;
        }

        $route = $this->request->getParam('_route');
        $serverstyleDash = $this->settingsService->getUserStyleDashboardEnabled();
        $serverstyleLogin = $this->settingsService->getServerStyleLoginEnabled();

        switch ($route) {
            case 'core.TwoFactorChallenge.showChallenge':
            case 'files_sharing.Share.authenticate':
            case 'files_sharing.Share.showAuthenticate':
                if($serverstyleLogin){
                    $this->addHeaderFor('login');
                }
                break;
            case 'files_sharing.Share.showShare':
                if($serverstyleDash) {
                    $this->addHeaderFor('dashboard');
                }
                break;
            case 'dashboard.dashboard.index':
                if($event->isLoggedIn() && $serverstyleDash) {
                    $this->addHeaderFor('dashboard');
                }
                break;
            default:
                if($event->isLoggedIn()) {
                    if($serverstyleDash) {
                        $this->addHeaderFor('dashboard');
                    }
                } else {
                    if($serverstyleLogin) {
                        $this->addHeaderFor('login');
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
    private function addHeaderFor(String $target) {
        $linkToCSS = $this->urlGenerator->linkToRouteAbsolute('unsplash.css.' . $target);

        Util::addHeader('link', [
            'rel' => 'stylesheet',
            'href' => $linkToCSS,
        ]);

        Util::addStyle('unsplash', $target.'_static');
    }

}
