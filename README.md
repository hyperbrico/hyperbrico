HYPERBRICO
========================

Repos for www.hyperbrico.pf

Getting started
----------------------------------

- Install msysgit
- Install xampp
- Clone the repos in xampp/htdocs/
- Set the PATH for php
- Download composer: php -r "eval('?>'.file_get_contents('http://getcomposer.org/installer'));"
- Create the user hyperbrico
- Create the database hyperbrico: php app/console doctrine:database:create
- Update the schema: php app/console doctrine:schema:update --force
