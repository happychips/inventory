<?php

use happy\inventory\app\HttpSession;
use happy\inventory\app\Launcher;
use watoki\karma\stores\StoringEventStore;
use watoki\stores\stores\FileStore;

require_once __DIR__ . '/vendor/autoload.php';

$usersFile = __DIR__ . '/user/users.json';
if (!file_exists($usersFile)) {
    if (!file_exists(dirname($usersFile))) {
        mkdir(dirname($usersFile), 0777, true);
    }
    file_put_contents($usersFile, '{}');
}

(new Launcher(
    new StoringEventStore(new FileStore(__DIR__ . '/user/data')),
    new HttpSession(),
    json_decode(file_get_contents($usersFile), true)
))->run();