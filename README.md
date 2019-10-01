# php-cms-editor

## Setup

Server

 - MySQL (MariaDB)
 - PHP 7.3 or later

Front
 
 - Node.js 10 or later
 - Yarn

Clone repo

```bash
$ git clone https://github.com/k725/php-cms-editor.git
$ cd php-cms-editor
```

Build front

```bash
$ cd frontend
$ yarn
$ yarn build
$ cd ..
```

Setup server

```bash
$ cp src/settings_sample.php src/settings.php
$ vim src/settings.php
$ composer install
$ composer run migrate
$ composer run start
```

Open a browser and go to `http://localhost:8080/articles`