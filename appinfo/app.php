<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

use OCA\Unsplash\AppInfo\Application;
use OCP\AppFramework\QueryException;

try {
    $app = new Application();
    $app->register();
} catch(QueryException $e) {
}