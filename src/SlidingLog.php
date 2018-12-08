<?php


namespace Pushwoosh\RateLimiting;


class SlidingLog implements RateLimitingInterface
{
    /**
     * @var int
     */
    protected $rate;

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * SlidingLog constructor.
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
        $interval = $currentTime - 1;
        $rows = [];
        foreach ($this->rows as $row) {
            if ($row >= $interval) {
                $rows[] = $row;
            }
        }

        $this->rows = $rows;

        if (\count($rows) < $this->rate) {
            $this->rows[] = $currentTime;
            return true;
        }

        return false;
    }

}
