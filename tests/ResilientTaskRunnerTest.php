<?php

namespace Eshta\ResilientTask\Tests;

use Eshta\ResilientTask\ResilientTaskRunner;
use PHPUnit\Framework\TestCase;
use ReflectionObject;

class ResilientTaskRunnerTest extends TestCase
{
    /**
     * @test
     */
    public function runnerRuns()
    {
        $executionTimes = 0;

        $procedure = function () use (&$executionTimes) {
            $executionTimes++;
            return $executionTimes;
        };

        $runner = new ResilientTaskRunner(50, 60, 0.5);

        $result = $runner->run($procedure);

        $this->assertEquals($executionTimes, $result);
        $this->assertEquals(1, $runner->getCurrentTries());
    }

    /**
     * @test
     */
    public function runAndSleep()
    {
        $executionTimes = 0;

        $procedure = function () use (&$executionTimes) {
            $executionTimes++;
            if ($executionTimes === 2) {
                return $executionTimes;
            } else {
                return;
            }
        };

        $runner = new ResilientTaskRunner(50, 60, 0.5);

        $runner->run($procedure);

        $this->assertEquals(2, $executionTimes);
        $this->assertEquals(2, $runner->getCurrentTries());
    }

    /**
     * @test
     */
    public function runUntilMaxRetries()
    {
        $executionTimes = 0;

        $procedure = function () use (&$executionTimes) {
            $executionTimes++;
        };

        $runner = new ResilientTaskRunner(1, 60, 0.5);

        $runner->run($procedure);

        $this->assertEquals($executionTimes, $runner->getCurrentTries());
    }

    /**
     * @test
     */
    public function runUntilMaxTriesAssertMaxSleepTime()
    {
        $executionTimes = 0;
        $maxSleepTime = 1;

        $procedure = function () use (&$executionTimes) {
            $executionTimes++;
        };

        $runner = new ResilientTaskRunner(3, $maxSleepTime, 0.5);

        $runner->run($procedure);

        $runnerReflection = new ReflectionObject($runner);
        $currentSleepTimeProperty = $runnerReflection->getProperty('currentSleepTime');
        $currentSleepTimeProperty->setAccessible(true);

        $this->assertEquals($executionTimes, $runner->getCurrentTries());
        $this->assertEquals($maxSleepTime, $currentSleepTimeProperty->getValue($runner));
    }
}
