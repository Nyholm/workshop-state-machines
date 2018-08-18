# Workshop: Knowing your State Machines

This branch will look at the Moore state machine and the state pattern.

## Run

```
php src/run.php
```

## Database

We have a very small json database in `/var/user.original.json`. That is a
read only database. It will automatically create `/var/user.json` with your
changes. Next time the application is running. It will use the new (/var/user.json`)
database. 

To restore the data to the original values. Please remove `/var/user.json`.

## Excesses 

We want to build a small application that send emails. The `Worker` fetches a set 
of `Users` from the database and creates and starts `StateMachineInterface` instances
for each user. 

From the `Worker`'s point of view, it just want to send emails. It is the state machine
that decides what email that should be sent. **Correction:** Since this is a Moore 
state machine, the *states* decide what emails should be sent.   

### 1. Create an implementation of `StateInterface`

Create a state that check if we should send an email to remind a user to add their name. 
If so, send that email with the `MailerService`. If not, use `SateMachineInterface::setState()`
to move to the next state. 

### 2. Create an implementations of `StateMachineInterface`

The purpose of our `StateMachineInterface` is to loop over all `StateInterface`
until some returns ``StateInterface::STOP`. 

Note that the state object may use the `StateMachineInterface::setState()`.

### 3. Create more implementations of `StateInterface`

Maybe it is a good idea to have emails for: 

* welcoming new users
* ask them about adding their email
* ask them about adding their Twitter

Remember that each state decide what the next state should be. 

### 4. When to stop?

The function `StateMachineInterface::start(): bool` returns boolean `true` if 
all the states has been executed for a user. Ie, there is no need to run a state 
machine with this user any more. 

Figure out a way to implement the return value of `StateMachineInterface::start()`.

### 5. Do not mail bomb the same user multiple times a day

We do not want any user to receive too many emails at once. Some states may wait
24 hours until they email the users. Some state may wait a week. Your task is to 
implement some kind check for this. 
 
The `WorkdClock` class is a good helper class that makes it easier to test your 
time limits. Just modify `WorldClock::$currentDate` to a different date. 

**Hint:** You may want to add one or more new properties to `User`.

### Bonus 1. Implement a non linear State Machine

Implement a bit more complex state machine. Something with states like: 

```
          C - D
        /      \
 A  -  B        G - H - I
        \      /
         E - F
```
