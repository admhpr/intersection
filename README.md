<div align="center" style="width: 200px">
    <img src="acf-buddy-logo.png" alt="ACF Buddy Logo">
</div>

```

```

WIP: Still in development

Simple Plugin to work with the 'Sectional Theme'

Devlopment Testing Setup

Along with [PHPunit 6.1](https://phpunit.de/manual/6.1/en/installation.html)

ensure [WP-CLI ](http://wp-cli.org/#install) is installed.

Run The install script located in the bin folder of this project with the following args `db-name` `db_user` `db_password` `db_host` `wordpress_version`

> There is an addtional final argument of boolean `true` which can be supplied if your test db is already setup

Example:

```console
user@computer /location/of/plugin $ bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

This will install the testing suite in the `/tmp` folder as

`/tmp/wordpress-tests-lib`

Along with a fresh install of wordpress.

You should now be able to run:

```console
user@computer /location/of/plugin $ phpunit
```

and see the results of PHPunit output to the console.
