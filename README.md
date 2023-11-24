# MK&G Ornament Explorer

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Allow MK&G museum visitors to explore ornamental arworks by visual similarity and metadata.

Developed by Igor Rjabinin and [Philo van Kemenade](https://github.com/phivk/) in partnership with [Museum für Kunst und Gewerbe Hamburg](https://www.mkg-hamburg.de/en) as part of the [NEO Lab Project](https://www.mkg-hamburg.de/en/neo-lab).

## Development

This software is built with the [Laravel framework](http://laravel.com/).

It requires

1. PHP >= 8
1. MySQL >= 5.7
1. Docker

### Weaviate db setup

1. Run the docker file 
    ```bash
    docker compose up
    ```
1. Run the schema command
    ```bash
    php artisan weaviate:create-schema
    ```
1. Index the data from database with images to weaviate
    ```bash
    php artisan weaviate:add-data
    ```

### Installation & local development

save `.env.exampole` as `.env`.

generate an application key:

```bash
php artisan key:generate
```

install back end dependencies

```bash
composer install
```

run a local back end development server

```bash
php artisan serve
```

install front end dependencies

```bash
npm install
```

run a local front end development server

```bash
npm run dev
```

## License

Source code in this repository is licensed under the MIT license. Please see the [License File](LICENSE) for more information.
