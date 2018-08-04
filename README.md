# Eloquent Service Injection
Simple trait to inject services into Eloquent models via a property.

[![Build Status](https://travis-ci.org/mrgrain/eloquent-service-injection.svg?branch=master)](https://travis-ci.org/mrgrain/eloquent-service-injection)
[![Latest Stable Version](https://poser.pugx.org/mrgrain/eloquent-service-injection/v/stable)](https://packagist.org/packages/mrgrain/eloquent-service-injection)
[![Total Downloads](https://poser.pugx.org/mrgrain/eloquent-service-injection/downloads)](https://packagist.org/packages/mrgrain/eloquent-service-injection)
[![License](https://poser.pugx.org/mrgrain/eloquent-service-injection/license)](https://packagist.org/packages/mrgrain/eloquent-service-injection)


Laravel's Eloquent models do not support constructor service injection. This trait aims to provide a simple and unified way of injection services into Eloquent models. It is doing that by using a property to define services, in a similar way how other model options can be defined (think `$casts` or `$with` for attribute casting and eager loading).

# Requirements
* PHP >= 5.6.0
* Laravel >= 5.1 or Lumen >= 5.1

# Installation
Add the package to your repository with Composer.
```
composer require mrgrain/eloquent-service-injection
```

# Usage
Extend your models by using the trait:
```php
namespace Mrgrain\EloquentServiceInjection;

class Comment extends Model {
    use ServiceInjectionTrait;
}
```
To always include the trait, add it to a base model all your models are extending from.

Add the services to be injected into the `$inject` property.
```php
class Comment extends Model {
    use ServiceInjectionTrait;
    
    public $inject = [
        'storage' => Illuminate\Contracts\Filesystem\Filesystem::class
    ];
}
```
Use the array key, to access the service anywhere.
```php
class Comment extends Model {
    use ServiceInjectionTrait;

    public $inject = [
        'storage' => Illuminate\Contracts\Filesystem\Filesystem::class
    ];
    
    public function storeAttachment($path, $contents)
    {
        $this->storage->put($path, $contents);
    }
}
```

## Other usages
Wheres the trait has been designed for Eloquent models, it can be used in pretty much any class. Add and use it the same way as with models.


# Contributing
First of all: Thank you for considering to contribute to this project. Without you the open source community would not be the same. If you decide to submit a pull request to this project, I'd kindly ask you to adhere to the following guidelines:

- Be aware of the scope of the project, to avoid disappointment. 
- Please add appropriate tests to all pull requests.
- When interacting on this project, follow the [Code of Conduct](CODE_OF_CONDUCT.md).
