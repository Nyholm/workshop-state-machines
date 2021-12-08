# Workshop: Knowing your State Machines

The mealy-x branches tries to build a Mealy state machine. Starting with a traffic
light an slowly moving towards something generic.

## Run

Install with 
```
composer install
```

Run with 
```
./vendor/bin/phpunit tests/StateMachineXTest.php
``` 

## Exercises

### Exercise 1

*(Restart from **branch mealy-1** if you want to)*

Implement a traffic light state machine. You do not *need* to modify any other classes 
than `TrafficLightStateMachine`. 

Use transition names: `to_green`, `to_yellow` and `to_red`. 

Run the following to test your implementation. (You may need to update the tests a little
to better fit your implementation.)

```bash
./vendor/bin/phpunit tests/StateMachine1Test.php
```

![Image of State Machine](/Resources/traffic.png)

### Exercise 1 (bonus)

In a country far far away, traffic lights are actually not this simple. They are green
to allow traffic. But before they turn red, they are red and yellow at the same time. 
Then when it is about to be green again it is red, then yellow, then green. 

Could you draw this new state machine on a paper?

### Exercise 2

*(Restart from **branch mealy-2** if you want to)*

This `TrafficLightStateMachine` is okey, but we have an issue with storing this state machine
in the database. Lets try to remove that issue by moving the `$state` property away from
`TrafficLightStateMachine` to a new `TrafficLight` class. 

After completing this exercise, we can use the same state machine object for multiple
different traffic light objects. 

Hint: You might need to create an interface. Example `StateAwareInterface`.

Note: You do not need to bother with actually storing any object in a database. 

### Exercise 3

We want our `TrafficLightStateMachine` to be more generic. Maybe rename it to `StateMachine`
and refactor away the transition definition to outside of the class. The `StateMachine` 
should take such definition as a constructor argument. 

After completing this exercise, we can use the same state machine object for many 
different objects. It is not tied to a traffic light anymore. 

Run the following to test your implementation. (You may need to update the tests a little
to better fit your implementation.)

```bash
./vendor/bin/phpunit tests/StateMachine3Test.php
```

### Exercise 4

*(Restart from **branch mealy-3** if you want to)*

It could be useful with a few extra functions. Please implement: 

* `StateMachine::getCurrentState(StateAwareInterface $object): string`
* `StateMachine::getValidTransitions(StateAwareInterface $object): array`

Run the following to test your implementation. (You may need to update the tests a little
to better fit your implementation.)

```bash
./vendor/bin/phpunit tests/StateMachine4Test.php
```
