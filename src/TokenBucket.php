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
        $this->timestamp = microtime(true);
    }

    /**
     * @param float $now
     * @return int
     */
    protected function getTokens(float $now): int {
        if ($this->tokens <= $this->rate) {
            $delta = $this->rate * ($now - $this->timestamp);

            $this->tokens = min($this->rate, $this->tokens + $delta);
            $this->timestamp = $now;
        }

        return (int) $this->tokens;
    }

    /**
     * @param float $currentTime
     * @return bool
     */
    public function canDoWork(float $currentTime): bool {
        if ($this->getTokens($currentTime)) {
            if ($this->tokens < 1) {
                return false;
            }

            $this->tokens--;
            return true;
        }

        return false;
    }
}

