# Cadastro de entidades para IntranetOne

Serviço customizado - site vitrinedoprofessor.com.br

## Conteúdo

## Instalação

```sh
composer require dataview/iovitrine
```

```sh
php artisan io-entity:install
```

- Configure o webpack conforme abaixo

```js
...
let vitrine = require('intranetone-vitrine');
io.compile({
  services:{
    ...
    new vitrine()
    ...
  }
});

```

- Compile os assets e faça o cache

```sh
npm run dev|prod|watch
php artisan config:cache
```
