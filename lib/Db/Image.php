<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Db;

use OCP\AppFramework\Db\Entity;

/**
 * Class Image
 *
 * @method getUuid()
 * @method setUuid(string $uuid)
 * @method getUrl()
 * @method setUrl(string $url)
 * @method getAvatarUrl()
 * @method setAvatarUrl(string $avatarUrl)
 * @method getSource()
 * @method setSource(string $source)
 * @method getAvatarSource()
 * @method setAvatarSource(string $avatarSource)
 * @method getProvider()
 * @method setProvider(string $provider)
 * @method getLink()
 * @method setLink(string $link)
 * @method getSubject()
 * @method setSubject(string $subject)
 * @method getCreator()
 * @method setCreator(string $creator)
 * @method getDescription()
 * @method setDescription(string $description)
 * @method getIsDark()
 * @method setIsDark(bool $isDark)
 *
 * @package OCA\Unsplash\Db
 */
class Image extends Entity {

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $avatarUrl;

    /**
     * @var string
     */
    protected $avatarSource;

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $creator;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var boolean
     */
    protected $isDark;

    /**
     * Folder constructor.
     */
    public function __construct() {
        $this->addType('uuid', 'string');
        $this->addType('url', 'string');
        $this->addType('avatarUrl', 'string');
        $this->addType('source', 'string');
        $this->addType('avatarSource', 'string');
        $this->addType('link', 'string');
        $this->addType('creator', 'string');
        $this->addType('subject', 'string');
        $this->addType('description', 'string');
        $this->addType('provider', 'string');
        $this->addType('isDark', 'boolean');
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return [
            'uuid'         => $this->getUuid(),
            'url'          => $this->getUrl(),
            'source'       => $this->getSource(),
            'avatar'       => $this->getAvatarUrl(),
            'avatarSource' => $this->getAvatarSource(),
            'link'         => $this->getLink(),
            'creator'      => $this->getCreator(),
            'subject'      => $this->getSubject(),
            'description'  => $this->getDescription(),
            'isDark'       => $this->getIsDark(),
            'provider'     => $this->getProvider()
        ];
    }
}