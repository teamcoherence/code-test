<?php

/**
 * This file is included very early. See autoload.files in composer.json and
 * https://getcomposer.org/doc/04-schema.md#files
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();