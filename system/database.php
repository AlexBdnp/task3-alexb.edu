<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$db = new Capsule;

$db->addConnection([
    'driver'    => 'mysql',
    'host'      => DB_HOST,
    'database'  => DB_NAME,
    'username'  => DB_USER,
    'password'  => DB_PASSWORD,
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
], 'default');

$db->setAsGlobal();

$db->bootEloquent();