# liberty-mini-mart

> my HackerU Web Developement Course Finals E-Commerce website.

## Build Setup

### Install PHP 7.1+, MariaDB 10.1.29+/equivelent MySQL, and Node 10+

``` bash
# install JavaScript dependencies
npm install

# create MySQL db with the name 'liberty' with the charset 'utf8' and collation 'utf8_unicode_ci'

# run key generation and db seeding
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed

# serve from localhost:8080 (or use '--port=<port>' to change the port number. )
php artisan serve

# build JavaScript for developement 
npm run dev

# build JavaScript for developement with rebuild on changes (may be buggy at this time)
npm run watch

# build for production with minification
npm run prod

# build for production and view the bundle analyzer report
npm run prod --report

# run unit tests (future feature)
npm run unit

# run e2e tests (future feature)
npm run e2e

# run all tests (future feature)
npm test
```

For a detailed explanation on how things work, check out the [guide (future feature)]() and [docs for vue-loader](http://vuejs.github.io/vue-loader).
