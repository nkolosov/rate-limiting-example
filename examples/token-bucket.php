<?php

require_once __DIR__ . '/../vendor/autoload.php';

$rate = 1000;
$filename = 'test.txt';

$worker = new \Pushwoosh\RateLimiting\Worker(new \Pushwoosh\RateLimiting\TokenBucket($rate), $filename);

while (true) {
    $worker->doWork(1);

    if ($worker->isComplete()) {
        break;
    }
}
