# Shopify Metafield-Advance App

### Installation
Install the dependencies and devDependencies.

For both environment
```sh
$ composer install
$ cp .env.example .env 
$ nano .env // set all credentials(ex: database, shopify api key and secret)
$ php artisan migrate
```

For development environments...

```sh
$ npm install
$ npm run dev
```
For production environments...

```sh
$ npm install --production
$ npm run prod
```
extra commands

```shell script
php artisan storage:link
```
### Used Shopify Tools

* Admin rest-api
* Shopify graphQl
* App-bridge
