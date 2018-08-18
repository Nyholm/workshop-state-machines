# Workshop: Knowing your State Machines

The mealy-x branches tries to build a Mealy state machine. Starting with a traffic
light an slowly moving towards something generic.

## Run

Install with (we need the auto loading) 
```
composer update
```

Run with 
```
./vendor/bin/phpunit
``` 

## Exercise

Implement a traffic light state machine. You do not *need* to modify any other classes 
than `TrafficLightStateMachine`. 

Use transition names: `to_green`, `to_yellow` and `to_red`. 

![Image of State Machine](/Resources/traffic.png)

## Bonus exercise 1

In a country far far away, traffic light are actually not this simple. They are green
to allow traffic. But before they turn red, they are red and yellow. Then when it is 
about to be green again it is red, then yellow, then green. 

