<?php

declare(strict_types=1);

namespace Tests\App;

use App\Entity\TrafficLight;
use App\StateMachine;
use Nyholm\NSA;
use PHPUnit\Framework\TestCase;

class StateMachineTest extends TestCase
{
    /** @var  StateMachine */
    private $sm;
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->sm = new StateMachine( [
            'green' => [
                'to_yellow' =>  'yellow',
            ],
            'yellow' => [
                'to_green' => 'green',
                'to_red' => 'red',
            ],
            'red' => [
                'to_yellow' =>  'yellow',
            ],
        ]);
    }


    /**
     * @dataProvider canProvider
     */
    public function testCan(string $currentState, string $transition, bool $result)
    {
        $trafficLight = new TrafficLight($currentState);
        if ($result) {
            $this->assertTrue($this->sm->can($trafficLight, $transition), sprintf('We should be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        } else {
            $this->assertFalse($this->sm->can($trafficLight, $transition), sprintf('We should NOT be allowed to do transition "%s" when in state "%s"', $transition, $currentState));
        }
    }

    /**
     * @dataProvider applyProvider
     */
    public function testApply(string $currentState, string $newState, string $exception = null)
    {
        $trafficLight = new TrafficLight($currentState);
        if (null !== $exception) {
            $this->expectException($exception);
        }

        $this->sm->apply($trafficLight, sprintf('to_%s', $newState));
        $this->assertEquals($newState, $trafficLight->getState(), sprintf('A state machine with state "%s" should have updated to "%s" after ->apply("%s")', $currentState, $newState, $newState));
    }

    public function testGetCurrentState()
    {
        foreach (['green', 'yellow', 'green'] as $state) {
            $this->assertEquals($state, $this->sm->getCurrentState(new TrafficLight($state)));
        }
    }

    /**
     * @dataProvider validTransitionProvider
     */
    public function testGetValidTransitions(string $currentState, array $transitions)
    {
        $result = $this->sm->getValidTransitions(new TrafficLight($currentState));
        $resultValues = array_values($result);
        sort($resultValues);

        $this->assertEquals($transitions, $resultValues);
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

    public function validTransitionProvider()
    {
        return [
            ['green', ['to_yellow']],
            ['red', ['to_yellow']],
            ['yellow', ['to_green', 'to_red']],
        ];
    }
}
