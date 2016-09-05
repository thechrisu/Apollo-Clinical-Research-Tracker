We put together a video to explain the application! Watch here!

<a href="http://www.youtube.com/watch?feature=player_embedded&v=BRYr1T7KGXc
" target="_blank"><img src="http://img.youtube.com/vi/BRYr1T7KGXc/0.jpg" 
alt="Youtube Video of our app!" width="480" height="360" border="10" /></a>

#Clinical Research Tracker

This repository contains the front-end and back-end source code for the Apollo Clinical Research Tracker. Made by UCL Computer Science students Desislava Koleva, Christoph Ulshoefer and Timur Kuzhagaliyev in 2016.

The `apollo-app` contains the main source code for the Apollo application, `docs` has the documentation generated using PHPDoc, `mockup` contains the functional mockups for the application

#Deployment Manual

## Prerequisites

This manual assumes that you have already installed Apache2 server and Composer dependency manager. These 2 pieces of software are commonly used nowadays and tutorials about their installation can be easily found online.

## Setting up an Apache virtual host

The process of setting up of a virtual host for Apollo application is standard, just follow the instructions relevant to your Apache installation and operating system. Don't forget to point the virtual host to  `path/to/ourapp/apollo-app/web` folder, as this folder contains the `index.php` entry script. This is the only point which should be exposed to the public. Additionally, it is very important to enable Apache rewriting engine. A tutorial on how to do so [can be found here](https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite-for-apache-on-ubuntu-14-04), but if it is not suitable for your particular installation then you can easily find a similar tutorial on the web.

## Installing dependencies

We use a dependency manager called Composer for installing dependencies. If you have not installed composer, please [do so](https://getcomposer.org/doc/00-intro.md). Once that step is done, the (real) dependencies can be installed by opening the terminal (or a similar command line), switching to the `apollo-app` directory and running the command `composer install`. If you will get any errors about the permissions, try using `sudo composer install`.

##  Importing the MySQL dumps

The MySQL dump of our database can be found in the file called `apollo.sql`. Simply create a database in your MySQL installation (using, say, phpMyAdmin) called `apollo` or something similar and execute the SQL code found inside the file to import the content.

TODO add information about installing mysql?

## Setting up the config for MySQL

You can find the config file located in `apollo-app/apollo/Config.example.php`. You can create a copy of this file and rename it into `Config.php`. Edit the resultant file to specify the credentials of your local MySQL server. Or, you have a choice to use the default values which will give you access to development MySQL server of Group 30 team members, but this one will be available only for a limited period of time (we will shut it down in May 2016).

## Running the application

Now the applicaiton should be good to go. Try accessing the URL you have specified in your Apache virtual host. The default user email is `test@test.com` and the password is `test`. Feel free to contact us if you experience any issues.

## Documentation and unit testing

Documentation for the PHP source code can be found in the `docs` folder in this repositroy. When you installed the dependencies, Composer automatically installed PHPUnit for you. You can run unit tests by simply executing the command `vendor/bin/phpunit` in your terminal, just make sure you current directory is `apollo-app.
