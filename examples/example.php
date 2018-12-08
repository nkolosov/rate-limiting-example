<?php

require_once __DIR__ . '/../vendor/autoload.php';

$rate = 1000;
$filename = '/tmp/rate-limit-example';
$worker = new \Pushwoosh\RateLimiting\Worker(new \Pushwoosh\RateLimiting\FixedWindow($rate), $filename);
while (true) {
    $worker->doWork(1);

    if ($worker->isComplete()) {
        break;
    }
}

printf("Actual Rate: %.6f\n", $worker->getActualRate());
