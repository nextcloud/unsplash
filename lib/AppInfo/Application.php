<?php

declare(strict_types=1);

/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\AppInfo;

use OCA\Unsplash\EventListener\AddContentSecurityPolicyEventListener;
use OCA\Unsplash\EventListener\BeforeTemplateRenderedEventListener;
use OCA\Unsplash\Services\LegacyInitialisationService;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Http\Events\BeforeLoginTemplateRenderedEvent;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

/**
 * Class Application
 *
 * @package OCA\Unsplash\AppInfo
 */
class Application extends App implements IBootstrap
{
    public const APP_ID = 'unsplash';

    /**
     * Application constructor.
     *
     * @param array $urlParams
     */
    public function __construct(array $urlParams = [])
    {
        parent::__construct(self::APP_ID, $urlParams);
    }

    public function register(IRegistrationContext $context): void
    {
        $context->registerEventListener(BeforeTemplateRenderedEvent::class, BeforeTemplateRenderedEventListener::class);
        $context->registerEventListener(BeforeLoginTemplateRenderedEvent::class, BeforeTemplateRenderedEventListener::class);
        $context->registerEventListener(AddContentSecurityPolicyEvent::class, AddContentSecurityPolicyEventListener::class);
    }

    public function boot(IBootContext $context): void
    {
    }
}
