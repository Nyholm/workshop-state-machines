<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

$worker = new \App\Worker(
    new \App\Service\Database(),
    new \App\Service\MailerService()
);

$worker->run();
