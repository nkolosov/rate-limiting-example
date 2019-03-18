<?php


namespace Pushwoosh\RateLimiting;


class TokenBucket implements RateLimitingInterface {
    /** @var int */
    private $rate;

    /** @var float */
    private $timestamp;

    /** @var float */
    private $tokens;

    /**
     * TokenBucket constructor.
     * @param int $rate
     */
    public function __construct(int $rate) {
        $this->rate = $rate;
        $this->tokens = $rate;
        $this->timestamp = microtime(true);
    }

    /**
     * @param float $currentTime
     * @return bool
     */
    public function canDoWork(float $currentTime): bool {
        $delta = $this->rate * ($currentTime - $this->timestamp);

        $this->tokens = min($this->rate, $this->tokens + $delta);
        $this->timestamp = $currentTime;

        if ($this->tokens < 1) {
            return false;
        }

        --$this->tokens;
        return true;
    }
}

