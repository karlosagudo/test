OAT PROJECT
===========

Symfony 5.x application.

Execute:
If you are using MacOs, just do a symfony serve
In case you are using Linux or Windows, please install the [correct symfony binary](https://symfony.com/download)

After execute it go to in your browser https://127.0.0.1:8000/questions?lang=en

###Composer scripts: 
Execute with composer <scriptname>, Example composer phpunit to run tests)
- phpunit : Will execute the tests
- precommit: Basics checks before commiting code

###Packages dependencies:
[stichoza/google-translate-php](https://github.com/Stichoza/google-translate-php) 
dev:
[friendsofphp/php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)


[CodeStyling Reference](docs/codeStyle.md)


###ersistence Managers

There is 2 infra/persistence managers, one for reader , another for writer purposes.
Both act as a factory to choose which reader or writer you should
use based on the **.env variables.**

