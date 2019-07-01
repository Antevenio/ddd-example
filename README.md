# Antevenio\'s DDD Example App
This is a pet project for DDD and Clean/Hexagonal architecture concepts.

# Index

1. [Introduction](#introduction)
2. [Domain/ Application / Infrastructure](#domain-application-infrastructure)
3. [Project setup](#project-setup)
4. [Services](#services)
5. [Composer / Unit Tests](#composer)
6. [HTTP UI](#http)
7. [Console UI](#console)
8. [Metrics](#metrics)

# <a name="introduction">Introduction</a>
We are trying to find our own way to create apps. Some parts are based on 
awesome DDD examples that we have recently found on Github: 
[dddinphp](https://github.com/dddinphp), 
[jorge07/symfony-4-es-cqrs-boilerplate](https://github.com/jorge07/symfony-4-es-cqrs-boilerplate), 
[CodelyTV/cqrs-ddd-php-example](https://github.com/CodelyTV/cqrs-ddd-php-example), and [many others](https://github.com/search?l=PHP&o=desc&q=ddd&s=stars&type=Repositories)

# <a name="domain-application-infrastructure">Domain / Application / Infrastructure</a>

## Domain
With very simple Domain: 

**User**(id, email) can be read and created.

Implemented concepts:
* Entities, ValueObjects, DomainEvents, Exceptions and Repositories.
* Model Validation with [Assert](https://github.com/beberlei/assert)
* UID generated with [ramsey/uuid](https://github.com/ramsey/uuid)
  
## Application
Actions: CreateUserAction and GetUserAction
* Action/Command Bus with [Tactician](https://github.com/thephpleague/tactician) and PDO transactional middleware.
* Event Store and Event Notifier with [Prooph](https://github.com/prooph/event-store) (PDO)
* Publishing Domain Events in a Rabbitmq Topic Exchange

## Infrastructure
* UI
  * Http Handlers with [Slim Framework](https://github.com/slimphp/Slim)
  * Console Commands with [Symfony Console](https://github.com/symfony/console)
* PSR's: 
  * PSR-2 Coding Style Guide (codesniffer in composer.json)
  * PSR-3 LoggerInterface (implemented with [Monolog](https://github.com/Seldaek/monolog))
  * PSR-4 Autoloader (autoload/autoload-dev in composer.json)
  * PSR-7 HTTP Messages (with Slim)
  * PSR-11 Container Interface (with Slim built-in container)
  * PSR-15 Http Handlers and Middlewares
* Metric\'s services implemented with [Prometheus Client](https://github.com/Jimdo/prometheus_client_php).
* Http Error Handler following [API Problem](https://github.com/Crell/ApiProblem) specification.
* Environment config with [zend-config](https://github.com/zendframework/zend-config)
* Development environment with Docker

# <a name="project-setup">Project setup</a>

1. [Install docker](https://www.docker.com/get-started)
2. Clone this repo:
   ```bash
   git clone https://github.com/jbarroso/ddd-example 
   cd ddd-example
   ```
3. Start development environment with docker-compose
   ```bash
   export UID && docker-compose up -d
   ```
# <a name="services">Services</a>
* Access to the **mysql** database
   ```bash
   docker-compose exec mysql mysql -u myuser -psecret example
   ```
* Access to **Rabbitmq** (guest/guest) [http://localhost:15672](http://localhost:15672)
* Access to **Prometheus** [http://localhost:9090](http://localhost:9090)
* Access to **Grafana** (admin/admin) [http://localhost:3000](http://localhost:3000)

# <a name="composer">Composer / Unit tests</a>
Install dependencies (they are already installed with docker-compose up)
```bash
docker-compose run php composer install 
```
Check coding style and run unit tests (phpunit)
```bash
docker-compose run php composer check 
```

# <a name="http">HTTP UI</a>

Say hello world!
```bash
curl -v -X GET "http://localhost/hello"
```
Create a user
```bash
curl -v -X "POST" "http://localhost/user"  -H "Content-Type: application/json" -H "Accept: 1.0" -d '{"email":"john.doe@antevenio.com"}'
```
Get a user
```bash
curl -v -X GET "http://localhost/user?id=1958964c-724e-11e9-80e9-0242ac120004"
```

# <a name="console">Console UI</a>
List all available commands
```bash
docker-compose run php bin/console
```
Say hello world!
```bash
docker-compose run php bin/console hello-world
```
Get a user
```bash
docker-compose run php bin/console get-user 1958964c-724e-11e9-80e9-0242ac120004
```
Notify events to Rabbitmq exchange: 
```bash
docker-compose run php bin/console notify-events
```
Start a Rabbitmq consumer listening to all events. 
For each event it will write a log entry in *logs/app.log* 
and also it will publis a counter metric with the event name 
in Prometheus.
```bash
docker-compose run php bin/console all-events-consumer
```
Start a Rabbitmq consumer listening to *UserWasCreated* event.
It will write a log entry in *logs/app.log*.
```bash
docker-compose run php bin/console user-was-created-consumer
```

# <a name="metrics">Metrics</a>
Following Prometheus concepts we have: counters, gauges and histograms.

* The application exposes a /metrics endpoint for Prometheus and for testing porpouse it
will update and publish a counter (example_some_counter), a gauge (example_some_gauge)
and an histogram (example_some_histogram).
* Also we have a middleware to messure HTTP Request duration (*LatencyMiddleware*).
It will publish example_request_duration_seconds histogram.
* *AllEventsConsumer* will increase a counter wit the event name 
(example_UserWasRead and example_UserWasCreated).
* And finally we have configured with Docker a [Rabbitmq Prometheus exporter](https://github.com/kbudde/rabbitmq_exporter) that you
can consume in Grafana with this dashboard: [Rabbitmq Graphana dashboard](https://grafana.com/dashboards/4279).

