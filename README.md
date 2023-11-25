# MK&G Ornament Explorer

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Allow MK&G museum visitors to explore ornamental arworks by visual similarity and metadata.

Developed by [Igor Rjabinin](https://github.com/igor-kamil/) and [Philo van Kemenade](https://github.com/phivk/) in partnership with [Museum für Kunst und Gewerbe Hamburg](https://www.mkg-hamburg.de/en) as part of the [NEO Lab Project](https://www.mkg-hamburg.de/en/neo-lab).

## Development

This software is built with the [Laravel framework](http://laravel.com/).

It requires

1. PHP >= 8
1. MySQL >= 5.7
1. Docker


### Installation & local development

save `.env.example` as `.env`.

generate an application key:

```bash
php artisan key:generate
```

install back end dependencies

```bash
composer install
```

run a local back end development server
(or use other solution. on mac we suggest to use [Herd](https://herd.laravel.com))

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

## Data

There are to options how to get data in the app

### Using seeder (quick & easy aproach for demo purposes)

Just seed the provided sample data using the command `php artisan db:seed`

### Process used for MK&G collection data

1. import item data from digikult export by: `php artisan import:items {file}` - check [ImportItems.php](app/Console/Commands/ImportItems.php) for details
2. import assets data from DAM export by: `php artisan import:assets {file}` - check [ImportAssets.php](app/Console/Commands/ImportAssets.php) for details
3. pair the items with their online collection url by: `php artisan import:urls {file}` - check [ImportUrls.php](app/Console/Commands/ImportUrls.php) for details

## Images

1. download the images for imported MK&G items by: `php artisan download:images`
2. generate tiny placeholder images for items by: `php artisan generate:tiny-placeholders`

## Weaviate db setup

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

## Admin

This app contains also admin panel (reachable on `{yourhost}/admin`) build using [Filament](https://filamentphp.com).

To create an user, run

```bash
php artisan make:filament-user
```

## License

Source code in this repository is licensed under the MIT license. Please see the [License File](LICENSE) for more information.
