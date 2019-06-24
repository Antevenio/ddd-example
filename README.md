# Antevenio\'s DDD Example App
This project is a sandbox for DDD concepts.

# Index

1. [Introduction](#introduction)
2. [Domain/ Application /Infrastructure](#domain-application-infrastructure)
3. [Project setup](#setup)

# <a name="introduction">Introduction</a>
This is a mix of some awesome project's that we have recently found on Github: [dddinphp](https://github.com/dddinphp), 
[jorge07/symfony-4-es-cqrs-boilerplate](https://github.com/jorge07/symfony-4-es-cqrs-boilerplate), 
[CodelyTV/cqrs-ddd-php-example](https://github.com/CodelyTV/cqrs-ddd-php-example), and [many others](https://github.com/search?l=PHP&o=desc&q=ddd&s=stars&type=Repositories)

# <a name="domain-application-infrastructure">Domain / Application / Infrastructure</a>

## Domain
* Entities, ValueObjects, DomainEvents, Exceptions and Repositories.
* Model Validation with [Assert](https://github.com/beberlei/assert)
* UID generated with [ramsey/uuid](https://github.com/ramsey/uuid)
  
## Application
* Event Store with [Prooph](https://github.com/prooph/event-store) (PDO)
* Action/Command Bus with [Tactician](https://github.com/thephpleague/tactician) and PDO transactional middleware.
* Publishing Domain Events in a Rabbitmq Topic Exchange

## Infrastructure
* Development environment with Docker
* UI
  * Http Handlers with [Slim Framework](https://github.com/slimphp/Slim)
  * Console Commands with [Symfony Console](https://github.com/symfony/console)
* Following PSR's: 
  * PSR-2 Coding Style Guide (codesniffer in composer.json)
  * PSR-3 LoggerInterface (implemented with [Monolog](https://github.com/Seldaek/monolog))
  * PSR-4 Autoloader (autoload/autoload-dev in composer.json)
  * PSR-7 HTTP Messages (with Slim)
  * PSR-11 Container Interface (with Slim built-in container)
  * PSR-15 Http Handlers and Middlewares
* Environment config with [zend-config](https://github.com/zendframework/zend-config)
 
# Project setup

1. [Install docker](https://www.docker.com/get-started)
2. Clone this repo:
```bash
git clone https://github.com/jbarroso/ddd-example 
cd ddd-example
```
3. Build docker image
```bash
docker build -t antevenio/example .
```

### Getting dependencies with composer:
docker run --volume $(pwd):/usr/src/app antevenio/example composer install

### Run unit tests (phpunit)
docker run --volume $(pwd):/usr/src/app antevenio/example composer check 

### Start enviroment with docker-composer
export UID && docker-compose up

### Access to the database
docker-compose exec mysql mysql -u myuser -psecret example

## Access to rabbitmq (guest/guest)
http://localhost:15672

## Testing HTTP API with curl

Say hello world!
```bash
curl -v -X GET "http://localhost/hello"
```

Create a user
```bash
curl -v -X "POST" "http://localhost/user"  -H "Content-Type: application/json" -H "Accept: 1.0" -d '{"email":"john.doe@antevenio.com"}'
```

Get an user
```bash
curl -v -X GET "http://localhost/user?id=1958964c-724e-11e9-80e9-0242ac120004"
```
## Test Console scripts

List all available commands
```bash
docker run --network=example_network --volume $(pwd):/usr/src/app antevenio/example bin/console

```

Say hello

```bash
docker run --network=example_network --volume $(pwd):/usr/src/app antevenio/example bin/console hello-world

```
Get an user
```bash
docker run --network=example_network --volume $(pwd):/usr/src/app antevenio/example bin/console get-user 1958964c-724e-11e9-80e9-0242ac120004
```

Notify events to rabbitmq 
```bash
docker run --network=example_network --volume $(pwd):/usr/src/app antevenio/example bin/console notify-events
```

