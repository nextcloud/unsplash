<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Db;

use OCP\AppFramework\Db\Entity;

/**
 * Class ImageInfo
 *
 * @method getUuid()
 * @method setUuid(string $uuid)
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
 * @package OCA\Unspash\Db
 */
class ImageInfo extends Entity {

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
        $this->addType('provider', 'string');
        $this->addType('link', 'string');
        $this->addType('subject', 'string');
        $this->addType('creator', 'string');
        $this->addType('description', 'string');
        $this->addType('isDark', 'boolean');
    }

    public function toArray() {
        return [
            'uuid'        => $this->getUuid(),
            'link'        => $this->getLink(),
            'creator'     => $this->getCreator(),
            'description' => $this->getDescription(),
            'isDark'      => $this->getIsDark(),
            'subject'       => $this->getSubject(),
            'provider'    => $this->getProvider()
        ];
    }
}