<?php


namespace Pushwoosh\RateLimiting;


class FixedWindow implements RateLimitingInterface
{
    /**
     * @var int
     */
    protected $rate;

    /**
     * @var array[]
     */
    protected $limits;

    /**
     * FixedWindow constructor.
     * @param int $rate
     */
    public function __construct(int $rate)
    {
        $this->rate = $rate;
    }

    /**
     * @param float $currentTime
     * @return bool
     */
    public function canDoWork(float $currentTime): bool
    {
        $time = (int) $currentTime;

        if (!isset($this->limits[$time])) {
            $this->limits = [];
            $this->limits[$time] = 0;
        }

        $this->limits[$time]++;

        return $this->limits[$time] <= $this->rate;
    }
}
