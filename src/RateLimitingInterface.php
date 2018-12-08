<?php


namespace Pushwoosh\RateLimiting;


interface RateLimitingInterface
{
    /**
     * RateLimitingInterface constructor.
     * @param int $rate
     */
    public function __construct(int $rate);

    /**
     * @param float $currentTime
     * @return bool
     */
    public function canDoWork(float $currentTime): bool;
}
