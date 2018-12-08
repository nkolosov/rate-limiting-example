<?php


namespace Pushwoosh\RateLimiting;


class Worker
{
    /**
     * @var float
     */
    protected $startTime;

    /**
     * @var float
     */
    protected $finishTime;

    /**
     * @var int
     */
    protected $processedCount = 0;

    /**
     * @var resource
     */
    protected $f;

    /**
     * @var \Pushwoosh\RateLimiting\RateLimitingInterface
     */
    protected $limiter;

    /**
     * FixedWindow constructor.
     * @param \Pushwoosh\RateLimiting\RateLimitingInterface $limiter
     * @param string $filename
     */
    public function __construct(RateLimitingInterface $limiter, string $filename)
    {
        $this->startTime = microtime(true);
        $this->limiter = $limiter;
        $this->f = fopen($filename ,'rb');
    }

    /**
     * @return void
     */
    protected function complete() {
        $this->finishTime = microtime(true);
        fclose($this->f);
    }

    /**
     * @return float
     */
    public function getActualRate(): float
    {
        return $this->processedCount  / ($this->finishTime - $this->startTime);
    }

    /**
     * @return bool
     */
    public function isComplete(): bool {
        return $this->finishTime !== null;
    }

    /**
     * @param float $quantum
     * @return mixed|void
     */
    public function doWork(float $quantum)
    {
        $finishTime = microtime(true) + $quantum;
        while (true) {
            $currentTime = microtime(true);
            if ($currentTime >= $finishTime) {
                return;
            }

            if (!$this->limiter->canDoWork($currentTime)) {
                return;
            }

            $result = fgets($this->f);
            if ($result === false) {
                $this->complete();
                return;
            }

            $this->processedCount++;
        }
    }
}
