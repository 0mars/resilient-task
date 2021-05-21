<?php

namespace Eshta\ResilientTask;

use InvalidArgumentException;

class ResilientTaskRunner implements RunnerInterface
{
    /**
     * A multiplier which decides how much time to back off after each loop instance
     * if it was 2, then the current sleep time will be 1, 2, 4...
     *
     * @var float
     */
    protected $backOffFactor;

    /**
     * @var float
     */
    protected $startingSleepTime;

    /**
     * Current sleep time (in seconds)
     *
     * @var float
     */
    protected $currentSleepTime;

    /**
     * Maximum time to sleep before retry (in seconds)
     *
     * @var int
     */
    protected $maxSleepTime;

    /**
     * Maximum number of tries before exiting
     *
     * @var int
     */
    protected $maxTries;

    /**
     * @var int
     */
    protected $currentTries = 0;

    /**
     * @param int   $maxTries
     * @param int   $maxSleepTime
     * @param float $startingSleepTime
     * @param float $backOffFactor
     */
    public function __construct(int $maxTries, int $maxSleepTime, float $startingSleepTime = 10, float $backOffFactor = 2)
    {
        $this->assertGreaterOrEqualThan(1, $maxTries, 'maxTries');
        $this->maxTries = $maxTries;
        $this->assertGreaterOrEqualThan(0, $maxSleepTime, 'maxSleepTime');
        $this->maxSleepTime = $maxSleepTime;
        $this->assertGreaterOrEqualThan(0, $startingSleepTime, 'startingSleepTime');
        $this->assertGreaterOrEqualThan(1, $backOffFactor, 'backOffFactor');
        $this->currentSleepTime = $this->startingSleepTime = $startingSleepTime/$backOffFactor;
        $this->backOffFactor = $backOffFactor;
    }

    /**
     * reset counters
     */
    protected function reset()
    {
        $this->currentSleepTime = $this->startingSleepTime;
        $this->currentTries = 0;
    }

    /**
     * @param callable $task
     *
     * @return mixed|null
     */
    public function run(callable $task)
    {
        $this->reset();
        while ($this->currentTries < $this->maxTries) {
            $result = $task();
            if ($result !== null) {
                return $result;
            }
            $this->currentTries++;
            sleep($this->getTimeToSleep());
        }
    }

    /**
     * @return int
     */
    protected function getTimeToSleep(): int
    {
        $newSleepTime = round($this->currentSleepTime * $this->backOffFactor);
        if ($newSleepTime >= $this->maxSleepTime) {
            $this->currentSleepTime = $this->maxSleepTime;
        } else {
            $this->currentSleepTime = $newSleepTime;
        }
        return $this->currentSleepTime;
    }

    /**
     * @return int
     */
    public function getCurrentTries(): int
    {
        return $this->currentTries;
    }

    /**
     * @return bool
     */
    public function maxTriesExhausted(): bool
    {
        return $this->currentTries === $this->maxTries;
    }

    /**
     * @param int    $min
     * @param int    $value
     * @param string $paramName
     */
    private function assertGreaterOrEqualThan(int $min, int $value, $paramName = '')
    {
        if ($value < $min) {
            throw new InvalidArgumentException(sprintf('%s : "%s" must be greater or equal than "%s"', $paramName, $value, $min));
        }
    }
}
