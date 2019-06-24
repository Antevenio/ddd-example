# Antevenio\'s DDD Example App
This project is a sandbox for ddd concepts

## Starting app with Docker
### Build php cli
docker build -t antevenio/example .

### Start dependencies
docker-compose up

### Access to the database
docker-compose exec mysql mysql -u myuser -psecret example

## Access to rabbitmq (guest/guest)
http://localhost:15672

## Test HTTP API with curl

Say hello
```bash
curl -v -X GET "http://localhost/hello"
```

Create an user
```bash
curl -v -X "POST" "http://localhost/user"  -H "Content-Type: application/json" -H "Accept: 1.0" -d '{"email":"jbarroso@antevenio.com"}'
```

Get an user
```bash
curl -v -X GET "http://localhost/user?id=1958964c-724e-11e9-80e9-0242ac120004"
```
## Test Console scripts

List all available commands
```bash
docker run --network=custom_network --volume $(pwd):/usr/src/app antevenio/example bin/console

```

Say hello

```bash
docker run --network=custom_network --volume $(pwd):/usr/src/app antevenio/example bin/console hello-world

```
Get an user
```bash
docker run --network=custom_network --volume $(pwd):/usr/src/app antevenio/example bin/console get-user 1958964c-724e-11e9-80e9-0242ac120004
```

Notify events to rabbitmq 
