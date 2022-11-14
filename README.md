# Getting started

## Description
New Reykjavik auto site (Intergalactic spaceship)

## Pre requisites
This project is using PHP 8.1 with TALL stack (Tailwind 3, Alpine, Livewire and Laravel 9) and Laravel mix 6

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

Alternative installation is possible without local dependencies relying on [Docker](#docker). 

Clone the repository

    git clone git@bitbucket.org:scandinavian/reykjavik-auto-new.git

Switch to the repo folder

    cd reykjavik-auto-new

Install composer dependencies

    composer install

Install npm dependencies

    npm install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Run the database seeder for translations. You can run it as much as you want.

    php artisan db:seed --class=LanguageLineSeeder

Run the database seeder for faqs. You should run it just once.

    php artisan db:seed --class=FaqsSeeder

There is a commmand to run the migrations and seeders altogether. (Alternative to the 3 "artisan" commands above)

    php artisan migrate:refresh --seed

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

## Translations

Translations are stored in database using https://github.com/spatie/laravel-translation-loader package. With this package, all laravel 'trans' function are available. You can mix using language files and the database. If a translation is present in both a file and the database, the database version will be returned.

The structure for the translations is always:

    'group' ->  web / admin / api

    'key'   -> 'key-group.key' where  'key-group' is something like 'general', 'reviews', 'home'

There is one Seeder for every 'group.key-group'. You can run LanguageLineSeeder to run all translations seeders (if you create a new one, include it in LanguageLineSeeder). You can run it as many times as you want since it uses the firstOrCreate method.

Please, note that 'group.key-group.key' must be unique and that all translations can be changed by users so as far as possible, don't include parameters in the translations so the user can not modify them.

## Model Translations

Model translations use the package https://github.com/spatie/laravel-translatable:4.6.0 (**version 4.6.0 is the one with compapatibility with PHP 7.4 and Laravel 8**)

## Tailwind

In order to start the Tailwind CLI build process in local environment to scan templates files or classes and build your css , run: 

    npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch

More information at https://tailwindcss.com/docs
