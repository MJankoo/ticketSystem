# Ticket System

## Table of contents
* [General info](#general-info)
* [Requirements](#requirements)
* [Setup](#setup)
  - [Database create and migration](#database-create-and-migration)
* [Usage](#setup)

## General Info
Simple ticket system written with Symfony 5

## Requirements
* PHP 7.4
* Symfony 5.4 with all its requirements
* Database
* Apache server

## Setup
```
$ git clone git@github.com:MJankoo/ticketSystem.git
$ cd ticketSystem
$ composer install
```
You also have to open .env file, and edit your database data.

### Database create and migration
```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
```

After that application is ready to use.
