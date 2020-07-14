# Cadastro customizado site vitrinedoprofessor.com.br

## Instalação

#### Composer installation

Laravel 7 or above, PHP >= 7.2.5

```sh
composer require dataview/iovitrine dev-master
```

laravel 5.6 or below, PHP >= 7 and < 7.2.5

```sh
composer require dataview/iovitrine 1.0.0
```

#### Laravel artisan installation

```sh
php artisan io-vitrine:install
```

## Webpack

```sh
let io = require('intranetone');
...
let vitrine = require('intranetone-vitrine');
io.compile({
  services:[
    ...
    new vitrine(),
  ]
});
```
