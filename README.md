# Workshop: Knowing your State Machines

The *symfony* branch uses the Workflow component.

## Run

Install with 
```
composer install
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
```

Make sure your database DSN is correct in `.env`. (Override them if you like by creating `.env.local`)

### Notes

**This "Notes" section is just an FYI. Start with Exercise 1 now.** 

If you update the workflow configuration, you will need to regenerate the
SVG by running the following command:

```
# For the traffic light
bin/console  workflow:build:svg state_machine.traffic_light
# For the article
bin/console  workflow:build:svg workflow.article
```

#### Extra requirements

To be able to dump workflows you need to install [Graphwiz](http://www.graphviz.org/)

#### Updates to database

If you update your models (files in `App\Entity`) you need to update your database. 
That is done with DoctrineMigrations.

```
# Create migration files: 
bin/console doctrine:migration:diff

# Execute the files
bin/console doctrine:migration:migrate
```

## Exercises

### Exercise 1

Configure a new state machine using a new `App\Workflow\TrafficLightFactory`. This class 
should be used as a [service factory](https://symfony.com/doc/current/service_container/factories.html).
You should create a new service named `state_machine.traffic_light_php` using this factory. 

```yaml
state_machine.traffic_light_php:
    public: true
    class: Symfony\Component\Workflow\StateMachine
    factory: 'App\Workflow\TrafficLightFactory:create'
```

Create a workflow that looks like this: 

![Image of State Machine](https://github.com/Nyholm/workshop-state-machines/raw/mealy-1/Resources/traffic.png)

The [this section of Symfony documentation](https://symfony.com/doc/current/components/workflow.html) might be helpful here.

### Exercise 2

Do the same thing as **Exercise 1** but use Symfony yaml config. 

```yaml
framework:
  workflows:
    traffic_light: 
      # ...
```


The [this section of Symfony documentation](https://symfony.com/doc/current/workflow.html#creating-a-workflow) might be helpful here.

### Exercise 3

Dump the state machines to make sure the configuration is correct. 
Use one of the following commands:

(replace "NAME" with the name of your workflow/state machine)

```bash
php bin/console workflow:dump NAME | dot -Tpng -o workflow.png
php bin/console workflow:dump NAME --dump-format=puml | java -jar plantuml.jar -p  > workflow.png
``` 

Example: 

```bash
bin/console workflow:dump traffic_light_php | dot -Tpng -o php.png
bin/console workflow:dump traffic_light     | dot -Tpng -o yaml.png
```

Now look at the file just created file "php.png" and "yaml.png. 

### Exercise 4

Implement an event subscriber for your state machine that logs all transitions. 

See it live:
```
bin/console server:start
```
1. Go to: http://127.0.0.1:8000/
2. Select "TrafficLight"
3. Create a TrafficLight object
4. Move around the states using the buttons. 

### Exercise 5

Implement an event subscriber that blocks an transition. Make sure only admins 
(ROLE_ADMIN) can execute that transition. 

### Exercise 6

Take a look at the `PullRequest` class and the `PullRequestController`. It is 
clearly a workflow here. 

1. Try to draw that workflow on a paper?
2. Make a change to your workflow
3. Could you make that same change to the code?
4. Refactor the `PullRequest` class and the `PullRequestController` to make it use
a state machine or a workflow.
5. Try to apply the same change (from bullet 2.) to your new implementation. 


### Exercise 7

Look at the `SignupController`. It is currently 4 steps to signup. That is a business 
requirement so we cannot change that. The business requires you to change the order
of the steps. 

1. See how the signup curretly works by visiting: http://127.0.0.1:8000/signup/start
2. Refactor the `SignupController` to use a workflow
3. Make sure we cannot "skip" a step by hacking the URLs
4. Make sure we use the workflow for `redirectToRoute`. (Use metadata on the workflow)


Example of using metadata:
```yaml
framework:
  workflows:
    signup:
       # ....
      places:
        name:
          metadata:
            route: signup_name
```
