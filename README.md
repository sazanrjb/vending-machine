# Vending Machine

Vending Machine is an application for simulating the actual vending machine. It consists of 3 products currently, namely Coke, Pepsi and Dew. One can simply purchase the product by inserting coin with specified amount.
The product can also be refunded. The purchase information can be seen in the list as well with the total coins in the machine and utilized coins.

## Framework

The application is written in PHP, on the [Laravel](http://laravel.com) framework. The current version used for this project is 8.12.

## Install
To use the application, follow the instructions below:
* `git clone git@github.com:sazanrjb/vending-machine.git`
* cd vending-machine
* Add `127.0.0.1 vending-machine.local` to your `/etc/hosts` file

## Docker Usage
* Make sure you have docker and docker-compose installed on you machine
* cd in: `cd vending-machine`
* Build/run it: `docker-compose up -d`
* Edit `.env` file for database credentials

## Set up project
To setup the application follow the below instructions:
* `cd vending-machine`
* `composer install`
* Give read/write permissions to `storage` and `bootstrap/cache` folders
* Go into the cli container: `docker-compose exec cli bash`
* Run migration: `php artisan migrate`
* Seed initial data: `php artisan db:seed`
* Run `npm i`  
* Access `http://vending-machine.local/` on browser

## Structure
* The application's structure is quite simple. The reside inside `VendingMachine` folder inside app folder
* Each modules reside in their own folder  
* Actions are used for the controllers inside Actions folder
* Eloquent models are inside Models folder
* Business logics reside in Manager file
* Other folders are used in the same manner for readability

## Frontend
* Vue.js is used in the frontend
* Ant Design framework is used for the UI components

## Tests
`PHPUnit` is used to write unit test. It is used in CI (Github actions) to automatically test after the push the branch.

To run unit test, go to the cli container, `docker-compose exec cli bash` and run:
```
./vendor/bin/phpunit
```

