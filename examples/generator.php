<?php

require_once __DIR__ . '/../vendor/autoload.php';

$file = fopen('test.txt', 'wb');

for ($i = 0; $i < 100; $i++) {
    fwrite($file, uniqid() . PHP_EOL);
}

fclose($file);

echo 'Completed' . PHP_EOL;
