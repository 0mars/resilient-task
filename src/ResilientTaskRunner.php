<?php
namespace Eshta\ResilientTask;

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
     * @param int $maxTries
     * @param int $maxSleepTime
     * @param int $startingSleepTime
     * @param int $backOffFactor
     */
    public function __construct($maxTries, $maxSleepTime, $startingSleepTime = 10, $backOffFactor = 2)
    {
        $this->maxTries = $maxTries;
        $this->maxSleepTime = $maxSleepTime;
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
            $this->currentTries++;
            if ($result !== null) {
                return $result;
            }
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
}
