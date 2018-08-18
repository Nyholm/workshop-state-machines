# Workshop: Knowing your State Machines

The mealy-x branches tries to build a Mealy state machine. Starting with a traffic
light an slowly moving towards something generic.

## Run

Install with 
```
composer update
```

Run with 
```
./vendor/bin/phpunit
``` 

## Exercises

### Exercise 1

*(Restart from **branch mealy-1** if you want to)*

Implement a traffic light state machine. You do not *need* to modify any other classes 
than `TrafficLightStateMachine`. 

Use transition names: `to_green`, `to_yellow` and `to_red`. 

![Image of State Machine](/Resources/traffic.png)

### Exercise 1 (bonus)

In a country far far away, traffic light are actually not this simple. They are green
to allow traffic. But before they turn red, they are red and yellow. Then when it is 
about to be green again it is red, then yellow, then green. 

### Exercise 2

*(Restart from **branch mealy-2** if you want to)*

This `TrafficLightStateMachine` okey. We have an issue with storing this state machine
in the database. Lets try to remove that issue by moving the state to a `TrafficLight` 
object. 

Hint: You might need to create an interface. 

### Exercise 3

We want our `TrafficLightStateMachine` to be more generic. Maybe rename it to `StateMachine`
and refactor away the transition definition to outside of the class. The `StateMachine` 
should take such definition as a constructor argument. 

### Exercise 4

*(Restart from **branch mealy-3** if you want to)*

It could be useful with a few extra functions. Please implement: 

* `StateMachine::getCurrentState(StateAwareInterface $object): string`
* `StateMachine::getValidTransitions(StateAwareInterface $object): array`
