# Bitcoin E-Commerce Store

*Proof of concept for a Bitcoin ecommerce site. This project is intended for small shops with few products.*

## Requirements
- Composer
- PHP 7.0+
- Apache or NGINX server.
- MySQL server

## Installation

Clone this repo to the server root directory.

```
$ git clone https://github.com/elvismdev/Bitcoin-E-Commerce-Store.git /srv/public_html/.
```

CD into the server root and install the application dependencies.

```
$ cd /srv/public_html/ && composer install
```

Make a copy of `parameters.yml.dist` to `parameters.yml` and edit/set in this last one the configuration details required.

```
$ cp app/config/parameters.yml.dist app/config/parameters.yml && nano app/config/parameters.yml
```

Run the commands below to generate an empty database for the shop:

```
$ php bin/console doctrine:database:create
```

```
$ php bin/console doctrine:schema:update --force
```

Point the server virtual host to `/srv/public_html/web/`
