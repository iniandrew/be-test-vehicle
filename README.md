# Junior BE Test - Andrew

> Disclaimer: I've been asked to Mrs. Evy to using another laravel and mongodb version, and she allowed me to use the another version.

I've been deploying this project to my server, you can access the project using the following link: [https://api.govomoon.com/](https://api.govomoon.com/)

## Table of Contents

- [Installation Guide](#installation-guide)
  - [Setup without Docker](#setup-without-docker)
  - [Setup using Docker](#setup-using-docker)


## Installation Guide

first clone the repository using the following command:
```bash
git clone https://github.com/iniandrew/be-test-vehicle.git
```

then, open the project folder
```bash
cd be-test-vehicle
```

### Setup without Docker

Because the project is using mongodb, you need to install mongodb on your local machine. You can follow the installation guide [here](https://docs.mongodb.com/manual/installation/)

Before we can install the MongoDB libraries for Laravel, we need to install the PHP extension for MongoDB. Run the following command:

```bash
sudo pecl install mongodb
```

You will also need to ensure that the mongodb extension is enabled in your php.ini file. The location of your php.ini file will vary depending on your operating system. Add the following line to your php.ini file:

`extension="mongodb.so"`

After that follow the following steps:

1. Copy the environment file
```bash
cp .env.example .env
```

After copying the environment file, you need to update the following variables in the `.env` file:

If you want to connect using the mongodb uri, uncomment the following line
```
# DB_URI=
# DB_DATABASE=laravel
```

If you want to connect using the host, port, database, username, and password, uncomment the following lines
```
# DB_HOST=127.0.0.1
# DB_PORT=27017
# DB_DATABASE=laravel
# DB_USERNAME=
# DB_PASSWORD=
```

adjust the values according to your mongodb configuration.

2. Install the dependencies
```bash
composer install
```

3. Generate the application key
```bash
php artisan key:generate
```

4. Generate the JWT secret
```bash
php artisan jwt:secret
```

5. If you want to seed the database with dummy data, run the following command:
```bash
php artisan db:seed
```

6. Run the application
```bash
php artisan serve
```


### Setup using Docker

Install Docker and Docker Compose on your local machine. You can follow the installation guide [here](https://docs.docker.com/get-docker/)

First, copy the environment file
```bash
  cp .env.docker .env
```

I have created a bash script to make it easier to install the project using docker. You can run the following command:
```bash
chmod +x ./install.sh &&
./install.sh
```

If you want to install the project manually, follow the following steps:

1. Run the docker container
```bash
docker compose up -d --build
```

2. Install the dependencies
```bash
docker compose exec -it app composer install
```

> After installing the dependencies, you can replace `docker compose exec -it app` with `./vendor/bin/sail` if you want to run the command using sail.

3. Generate the application key
```bash
docker compose exec -it app php artisan key:generate
```

4. Generate the JWT secret
```bash
docker compose exec -it app php artisan jwt:secret
```

If you want to seed the database with dummy data, run the following command:
```bash
docker compose exec -it app php artisan db:seed
```

> Note:
> 
> If you encounter an error like this when running the application using docker:
> `file_put_contents(/var/www/html/storage/framework/views/**.php): Failed to open stream: Permission denied`
> 
> You can run the following command:
> ```
> ./vendor/bin/sail root-shell
> ```
> then
> ```
> cd ../ && chown -R sail:sail html
> ```
