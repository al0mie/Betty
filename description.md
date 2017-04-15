Table structure
```sql
CREATE TABLE `bettings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `origin_guid` char(36) NOT NULL DEFAULT '',
  `opponent_guid` char(36) DEFAULT NULL,
  `game_id` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `start` int(11) unsigned NOT NULL DEFAULT '0',
  `end` int(11) unsigned NOT NULL DEFAULT '0',
  `amount` int(11) unsigned NOT NULL DEFAULT '0',
  `winner` tinyint(4) DEFAULT NULL,
  `origin_score` int(11) DEFAULT NULL,
  `opponent_score` int(11) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);
```
This is a solution for implementing betting game. Since required technologies was not defined, for development was used php language without any frameworks or libraries because it could be overheavy, so implement a custom architecture. Except phpunit for unit tests and php-di for injecting modules;

About structure of the project:

app - contains all logic of project. There you can find controllers, services, repositories. For connection to databases was used pdo layer and written some interface for him for convenient using.

bootstrap - There is start of the project and contains some methods for processing routes and exceptions.

config - It contains the config file for connect to db.

resources - It contains templates and could contains some other resource for builds.

routes - contains file with routes.

schedules - contains tasks for cron job.

tests - contains unit tests.

web - root of the application.

After cloning repository it required install dependencies via composer

``` composer install  ```

For running tests

```vendor/bin/phpunit```


For implementing periodic script to check finish of game was written script and automate execution via cron job. I guess using demon processes or external tools could be problematic.
The script is located in the schedule folder in the root.
To setup cron job in linux server you need open cronjob file (you can run command crontab -e to open him) and write period execution (I guess 1 minute will be fine to avoid leak intervals), path to php and path to script. Example:

```* * * * * /usr/bin/php /home/Bettings/schedules/TimeChecker.php```

For any questions, it's possible to use issue tracking.
