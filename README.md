# di

__namespace:__ `meow\di`

Dependency injection container for PHP

[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/D1D5DMOTA)

## Installation

To install this plugin use following command

```bash
composer require meow/di
```

## Usage

Example how to use this container

### Creating new container

To create new container you can use code as follows

```php
$container = new ApplicationContainer();
```

### Registering services

Services are defined as array `[interface => class]` and have to be defined
before you can resolve your classes.

```php
protected array $services = [
    BaseServiceInterface::class => BaseService::class
];

// add services to container
foreach ($this->services as $k => $v) {
    $container->set($k, $v);
}
```

### Resolving classes

With DI container you don't need to create new instances in constructor
(as in example controller - check tests).

```php
class MainController
{
    protected User $user;

    protected BaseServiceInterface $service;

    public function __construct(BaseServiceInterface $service, User $user)
    {
        $this->user = $user;
        $this->service = $service;
    }
}
```

now you can resolve controller with container

```php
/** @var MainController $controller */
$controller = $container->resolve(MainController::class);
```

__License: MIT__
