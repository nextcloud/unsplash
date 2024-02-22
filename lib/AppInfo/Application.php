<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\AppInfo;

use OCA\Unsplash\EventListener\AddContentSecurityPolicyEventListener;
use OCA\Unsplash\EventListener\BeforeTemplateRenderedEventListener;
use OCA\Unsplash\Services\LegacyInitialisationService;
use OCP\AppFramework\App;
use OCP\AppFramework\Http\Events\BeforeLoginTemplateRenderedEvent;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

use OCP\Util;

/**
 * Class Application
 *
 * @package OCA\Unsplash\AppInfo
 */
class Application extends App {

    /**
     * Application constructor.
     *
     * @param array $urlParams
     */
    public function __construct(array $urlParams = []) {
        parent::__construct('unsplash', $urlParams);
        $this->registerSystemEvents();
    }

    /**
     *
     */
    protected function registerSystemEvents() {
        $container = $this->getContainer();

        /* @var IEventDispatcher $eventDispatcher */
        $dispatcher = $this->getContainer()->get(IEventDispatcher::class);
        $dispatcher->addServiceListener(BeforeTemplateRenderedEvent::class, BeforeTemplateRenderedEventListener::class);
        $dispatcher->addServiceListener(BeforeLoginTemplateRenderedEvent::class, BeforeTemplateRenderedEventListener::class);
        $dispatcher->addServiceListener(AddContentSecurityPolicyEvent::class, AddContentSecurityPolicyEventListener::class);
    }
}
