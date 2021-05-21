<?php

namespace Eshta\ResilientTask\Tests;

use Eshta\ResilientTask\ResilientTaskRunner;
use InvalidArgumentException;
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

        $this->assertTrue($runner->maxTriesExhausted());
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

    /**
     * @test
     */
    public function testInvalidArgumentForMaxTries()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxTries : "0" must be greater or equal than "1"');
        new ResilientTaskRunner(0, 0);
    }

    /**
     * @test
     */
    public function testInvalidArgumentForMaxSleepTime()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxSleepTime : "-1" must be greater or equal than "0"');
        new ResilientTaskRunner(1, -1);
    }

    /**
     * @test
     */
    public function testInvalidArgumentStartingSleepTime()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('startingSleepTime : "-1" must be greater or equal than "0"');
        new ResilientTaskRunner(1, 0, -1);
    }

    /**
     * @test
     */
    public function testInvalidArgumentBackOffFactor()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('backOffFactor : "0" must be greater or equal than "1"');
        new ResilientTaskRunner(1, 0, 0, 0);
    }
}
