# Workshop: Knowing your State Machines

The symfony-x branches uses the Workflow component.

## Run

Install with 
```
composer install
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
```

Make sure your database DNS is correct in `.env`. 

If you update the workflow configuration, you will need to regenerate the
SVG by running the following command:

```
# For the task
bin/console  workflow:build:svg state_machine.traffic_light
# For the article
bin/console  workflow:build:svg workflow.article
```

### Extra requirements

To be able to dump workflows you need to install [Graphwiz](http://www.graphviz.org/)

### Updates to database

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

Configure a new state machine using `App\Workflow\TrafficLightFactory`. This class 
should be used as a [service factory](https://symfony.com/doc/current/service_container/factories.html)
You should create a new service named `app.state_machine.traffic_light` using this factory. 

Create a workflow that looks like this: 

![Image of State Machine](https://github.com/Nyholm/workshop-state-machines/raw/mealy-1/Resources/traffic.png)

To verify your factory implementation. Run command: 

```bash
bin/console workflow:dump app.state_machine.traffic_light | dot -Tpng -o dump.png
```

The [Symfony documentation](https://symfony.com/doc/current/components/workflow.html) might be helpful here.

### Exercise 2

Do the same thing as **Exercise 1** but use Symfony config. 

```yaml
framework:
  workflows:
    traffic_light: 
      # ...
```

To verify your configuration. Run command: 

```bash
bin/console workflow:dump app.state_machine.traffic_light | dot -Tpng -o dump.png
```


The [Symfony documentation](https://symfony.com/doc/current/workflow/state-machines.html) might be helpful here.

