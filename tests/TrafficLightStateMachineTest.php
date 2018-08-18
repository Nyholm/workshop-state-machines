<?php

declare(strict_types=1);

namespace Tests\App;

use App\TrafficLightStateMachine;
use Nyholm\NSA;
use PHPUnit\Framework\TestCase;

class TrafficLightStateMachineTest extends TestCase
{
    /**
     * @dataProvider canProvider
     */
    public function testCan(string $currentState, string $transition, bool $result)
    {
        $sm = new TrafficLightStateMachine();

        NSA::setProperty($sm, 'state', $currentState);
        if ($result) {
            $this->assertTrue($sm->can($transition), sprintf('We should be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        } else {
            $this->assertFalse($sm->can($transition), sprintf('We should NOT be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        }
    }

    /**
     * @dataProvider applyProvider
     */
    public function testApply(string $currentState, string $newState, string $exception = null)
    {
        $sm = new TrafficLightStateMachine();
        if (null !== $exception) {
            $this->expectException($exception);
        }

        NSA::setProperty($sm, 'state', $currentState);
        $sm->apply(sprintf('to_%s', $newState));
        $this->assertEquals($newState, NSA::getProperty($sm, 'state'), sprintf('A state machine with state "%s" should have updated to "%s" after ->apply("%s")', $currentState, $newState, $newState));
    }



    public function canProvider()
    {
        return [
            ['green', 'to_green', false],
            ['green', 'to_yellow', true],
            ['green', 'to_red', false],
            ['yellow', 'to_green', true],
            ['yellow', 'to_yellow', false],
            ['yellow', 'to_red', true],
            ['red', 'to_green', false],
            ['red', 'to_yellow', true],
            ['red', 'to_red', false],
            ['red', 'foobar', false],
        ];
    }

    public function applyProvider()
    {
        return [
            ['green', 'green', \InvalidArgumentException::class],
            ['green', 'yellow'],
            ['green', 'red', \InvalidArgumentException::class],
            ['yellow', 'green'],
            ['yellow', 'yellow', \InvalidArgumentException::class],
            ['yellow', 'red'],
            ['red', 'green', \InvalidArgumentException::class],
            ['red', 'yellow'],
            ['red', 'red', \InvalidArgumentException::class],
            ['red', 'foobar', \InvalidArgumentException::class],
        ];
    }
}
