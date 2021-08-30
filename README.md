## Laravel Shop (Laravel PHP framework application using Elasticsearch and Redis)

## Installation

Copy .env from .env.example file and then write your database config

```bash
cp .env.example .env
```

For example:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
Config Redis:

```
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```
Config Elasticsearch:

```
ELASTICSEARCH_ENABLED=true
ELASTICSEARCH_HOSTS="localhost:9200"
```
Config RabbitMQ
```
QUEUE_CONNECTION=rabbitmq
RABBITMQ_DSN=amqp://
RABBITMQ_HOST=127.0.0.1
RABBITMQ_USER=admin
RABBITMQ_PASSWORD=password
RABBITMQ_QUEUE=jobs
```
Run composer install

```bash
composer install
```

Run migrations and seeders

```bash
php artisan migrate --seed
```

Run elastic indexing

```bash
php artisan search:reindex
```

Run:

```bash
php artisan serve
```
Enter in browser:

```bash 
localhost:8000
```

For starting queue of tasks:
```
php artisan queue:work
```
