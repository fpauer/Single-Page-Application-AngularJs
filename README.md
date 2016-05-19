#Pre-requisites:
- PHP >= version 5.x
- LARAVEL version 5.x
- MYSQL >= version 14.x
- AngularJs version 1.5.x

#Installation:

1 - Clone the project

	git clone username@github.com/fpauer/Single-Page-Application-AngularJs.git

2 - Create the Database

	- Create a new schema inside some mysql database

3 - Configure the file .ENV inside the project folder /your/path/calories_app/.env
	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=caloriesdb
	DB_USERNAME=root
	DB_PASSWORD=root

4 - Create the tables and seed them

	cd /your/path/calories_app
	
	php artisan migrate:refresh --seed

5 - Run the unit tests
	
	phpunit


#Users and roles

Admin
email: admin@admin.com
password: @dmin1

Manager
email: manager@manager.com
password: m@nager1

User
email: test@test.com
password: te$te1



