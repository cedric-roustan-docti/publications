<?php

require __DIR__.'/setup.php';

/** @var \Interfaces\Shared\Database $database */
$database = $dic->get_object('database');
$connection = $database->get_connection();

@$connection->exec('ALTER TABLE project ADD description TEXT');

$connection->exec('CREATE TABLE IF NOT EXISTS config_recipients(
							email TEXT)');
$connection->exec('CREATE UNIQUE INDEX IF NOT EXISTS config_recipient ON config_recipients (email)');