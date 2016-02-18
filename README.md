#Clinical Research Tracker

This repo contains the front-end and back-end source code for the Clinical Research Tracker. Made by UCL Comp Sci students in 2016. 

## Note about `apollo-app`

To decrease the amount of files transfered to the repository the Composer folder `vendor` is ignored by Git, hence the app will not run out of the box. Make sure to run `composer install` before trying to run the app.

Additionally, make sure that the `fileinfo` PHP module is enabled. Usually this just means uncommenting `extension=fileinfo.so` or `extension=php_fileinfo.dll` in your `php.ini`.