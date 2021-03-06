Firebase Cloud Messaging
=========
** Caveat: This package is not maintained anymore.

This Package is to enable sending push notifications to devices with firebase cloud messaging. Tested in Laravel 5.1 and 5.2

#Installation
----

To install the package simply run this command in Laravel project root folder

```php
php composer.phar require saikatdutta1991/firebasecloudmessaging:1.0.0
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

add the fcm `server_key` in the `config/firebase_cloud_messaging.php` file
```php 
    return [
        
        "server_key" => env('FIREBASE_CLOUD_MESSAGING_SERVER_KEY'),
        "fcm_push_url" => env("FIREBASE_CLOUD_MESSAING_URL")

    ];

```

`fcm_push_url` is not required already included in package. If want to override then add it


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



# Example

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use PushManager;

class Controller extends Controller
{
    
    public function __construct(PushManager $pushManager)
    {
        $this->pushManager = $pushManager;
    }

    public function sendPushNotification()
    {
        $response = $this->pushManager
            ->setTitle('Test Title')
            ->setBody('Test Body')
            ->setIcon('icon url')
            ->setClickAction('https://www.google.com')
            ->setCustomPayload(['custom_data' => 'custom_data_array']) 
            ->setPriority(PushNotification::HIGH)
            ->setContentAvailable(true)
            ->setDeviceTokens('--------------------') // this can be an array or string
            ->push();

        dd( $response );
    }

}
```


To change response 
-----------

```php
->push(PushManager::STDCLASS)
```

`PushManager::STDCLASS`, `PushManager::ARRY`, `PushManager::RAW`

by default response is `PushManager::RAW` set



# Error Handle
===============

```php
$this->pushManager->getLastErrorCode() //if no error then 0
```
and 
```php
$this->pushManager->getLastErrorMessage() // if no error then ""
``` 


# Future Enhancement
======================

Now only raw response works proper. Json and stdClas response doesn't work for all types of fcm response. Returns null if failed to make json decode.


I will make the send push notification send asynchronous so that no wait for the response

