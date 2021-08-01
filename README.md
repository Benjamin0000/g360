## About Project
This project was built using the PHP Laravel framework

## Learning Laravel
Go to laravel.com

## Project Configuration
All the project configuration, api keys must be set in the .env file.

## Project APIs
This project uses APIs from epayment.ng && monnify.com
monnify.com is implemented for the virtual accounts functionalities while the 
epayment.ng API is provides api for VTU, cable tv subscription, Utility bill subscription and
bank account fund transfer.

## Projects Automated functionalities 
All automated proccesses in this project, like the Gsteam wheel, Ranking, Investment
are all handle by a cronjob that runs every 1minutes. this cron job runs this command [* * * * * cd /var/www/g360 && php artisan schedule:run >> /dev/null 2>&1] that 
enables is to be possible, You can tweek this as you deam feet.

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
