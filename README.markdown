Simple security wrapper for symfony 2 projects.

 * Has unit tests: no
 * Vendors: Symfony 2 Class Loader component, Symfony security component


Advantages
-----------------



Installation
-----------------

If you use a deps file, you could add:

 <pre>
 [security-extension]
     git=https://github.com/fightmaster/security-extension.git
 </pre>

Or if you want to clone the repos:

 <pre>
 git clone https://github.com/fightmaster/security-extension.git vendor/security-extension
 </pre>

Add the namespace to your autoloader

```php
<?php
 $loader->registerNamespaces(array(
     ............
     'Fightmaster'   => __DIR__.'/../vendor/security-extension/src',
     ............
 ));

```
