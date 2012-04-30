Documentation
-----------------

Status project: WIP - Work In Progress

Installation
-----------------

If you use a deps file, you could add:

 <pre>
 [FightmasterSecurityExtensionBundle]
     git=https://github.com/fightmaster/FightmasterSecurityExtensionBundle.git
     target=vendor/bundles/Fightmaster/SecurityExtensionBundle
 </pre>

Or if you want to clone the repos:

 <pre>
 git clone https://github.com/fightmaster/FightmasterSecurityExtensionBundle.git vendor/bundles/Fightmaster/SecurityExtensionBundle
 </pre>

Add the namespace to your autoloader

```php
<?php
 $loader->registerNamespaces(array(
     ............
     'Fightmaster'   => __DIR__.'/../vendor/bundles',
     ...........
 ));

```

