<?php

namespace Eshta\ResilientTask;

interface RunnerInterface
{
    /**
     * Executes a callable until it returns a non-null result
     *
     * @param callable $task
     *
     * @return mixed|null
     */
    public function run(callable $task);

    /**
     * Concludes a failure after exhausting all tries
     *
     * @return bool
     */
    public function maxTriesExhausted(): bool;
}
