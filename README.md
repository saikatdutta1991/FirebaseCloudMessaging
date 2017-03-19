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


# Configuration
publish the package config to your laravel config folder with executing command

```json
php artisan vendor:publish --tag="config"
```

before executing this command add the service provider first


# Usage
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



##dependency injection in controller constructor
-----------------------------

```php
class YourController extends Controller
{
    
    public function __construct(PushManager $pushManager)
    {
        $this->pushManager = $pushManager;
    }
```



If want to use without injecting then
```php
class YourController extends Controller
{
    
    public function sendPushNotification()
    {
        $pushManager = app('PushManager'); // this will keep the PushManager instance singleton
    }
```
