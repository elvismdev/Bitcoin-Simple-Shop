# Bitcoin E-Commerce Store

*Proof of concept for a Bitcoin ecommerce site. This project is intended for small shops with few products.*


##### Database creation
Set DB parameters on `parameters.yml` and run the commands below from this project root:

```
php bin/console doctrine:database:create
```

```
php bin/console doctrine:schema:update --force
```