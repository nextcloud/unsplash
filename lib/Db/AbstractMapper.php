<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Db;

use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\Mapper;
use OCP\IDBConnection;

/**
 * Class AbstractMapper
 *
 * @package OCA\Unspash\Db
 */
abstract class AbstractMapper extends Mapper {

    const TABLE_NAME = 'unspash';

    /**
     * AbstractMapper constructor.
     *
     * @param IDBConnection $db
     */
    public function __construct(IDBConnection $db) {
        parent::__construct($db, static::TABLE_NAME);
    }

    /**
     * @param string $uuid
     *
     * @return Entity
     *
     * @throws \OCP\AppFramework\Db\DoesNotExistException
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
     */
    public function findByUuid(string $uuid): Entity {
        list($sql, $params) = $this->getStatement();

        $sql      .= ' WHERE `uuid` = ?';
        $params[] = $uuid;

        return $this->findEntity($sql, $params);
    }

    /**
     * @param string $subject
     *
     * @return Entity[]
     */
    public function findAllBySubject(string $subject): array {
        list($sql, $params) = $this->getStatement();

        $sql      .= ' WHERE `subject` = ?';
        $params[] = $subject;

        return $this->findEntities($sql, $params);
    }

    /**
     * @return Entity[]
     */
    public function findAll(): array {
        list($sql, $params) = $this->getStatement();

        return $this->findEntities($sql, $params);
    }

    /**
     * @return array
     */
    protected function getStatement(): array {
        $sql = 'SELECT * FROM `*PREFIX*'.static::TABLE_NAME.'`';

        return [$sql, []];
    }
}