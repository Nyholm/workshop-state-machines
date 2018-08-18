# Workshop: Knowing your State Machines


## Run

```
composer install
php src/run.php
```


## Database

We have a very small json database in `/var/user.original.json`. That is a
read only database. It will automatically create `/var/user.json` with your
changes. Next time the application is running. It will use the new (/var/user.json`)
database. 

To restore the data to the original values. Please remove `/var/user.json`.

