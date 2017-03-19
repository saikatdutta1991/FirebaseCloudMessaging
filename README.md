Firebase Cloud Messaging
=========

This Package is to enable sending push notifications to devices with firebase cloud messaging. Tested in Laravel 5.1 and 5.2

#Installation
----

To install the package simply run this command in Laravel project root folder

```php
php composer.phar require --dev saikatdutta1991/firebasecloudmessaging:1.0.0
```


add the service provider 
```php 

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
            
        .....
        .....
        \Saikat\FirebaseCloudMessaging\FCMServiceProvider::class 
    ]
``` 
to `config\app.php` providers array  


Alias the PushManager class adding it to the aliases array in the `config/app.php` file.

```php
    'aliases' => [
        ...
        'PushManager' => Saikat\FirebaseCloudMessaging\PushManager::class
    ]
```

But the alias is not mandatory.



#Usage
-----------


##including PushManager
--------------------------

If added in alias then use it in controller
```php
use PushManager;
```

IF not added in alias then use it in controller
```php
use Saikat\FirebaseCloudMessaging\PushManager;
```
