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
``` 
to `config\app.php` providers array  
