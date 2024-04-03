# Friend Finder - Contact Information service

<!-- TOC -->

- [Friend Finder - Contact Information service](#friend-finder---contact-information-service)
  - [Environment file](#environment-file)
  - [Database commands](#database-commands)
  - [Docker setup](#docker-setup)

<!-- /TOC -->

## Environment file
This application uses a `env.php` file to store environment variables and is autoloaded by composer. Because of this, it is important that this file always exist before trying to run this application.

## Database commands
This project uses [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html) and [Doctrine Migrations](https://www.doctrine-project.org/projects/migrations.html) for all database operations. A command line tool can be found inside the `bin` folder that allows a developer to run migrations and manage the database schema.

The command line tool can be run by executing the following command from this application's root directory:

```bash
$ php bin/doctrine
```

The result of the command above will be a help page that displays all possible commands that can be executed. A developer can add their own [Symfony console commands](https://www.doctrine-project.org/projects/doctrine-orm/en/2.16/reference/tools.html#adding-own-commands) by editing the `bin/doctrine` file.

## Docker setup
First, build the container:

```bash
$ sudo docker build ../ -f ./Dockerfile -t s6-friend-finder-service-contact-information:latest
```

Then run it:
```bash
$ sudo docker run -it -p 127.0.0.1:8080:80 -td s6-friend-finder-service-contact-information:latest
```
