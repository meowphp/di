<?php

namespace Meow\DI\Tests;

use Meow\DI\ApplicationContainer;
use Meow\DI\Tests\Application\Controller\MainController;
use Meow\DI\Tests\Application\Model\User;
use Meow\DI\Tests\Application\Services\BaseService;
use Meow\DI\Tests\Application\Services\BaseServiceInterface;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected array $services = [
        BaseServiceInterface::class => BaseService::class
    ];

    public function testContainerResolve(): void
    {
        $container = new ApplicationContainer();

        foreach ($this->services as $k => $v) {
            $container->set($k, $v);
        }

        /** @var MainController $controller */
        $controller = $container->resolve(MainController::class);

        $this->assertInstanceOf(MainController::class, $controller);
        $this->assertInstanceOf(BaseService::class, $controller->getService());
        $this->assertInstanceOf(User::class, $controller->getUser());

        $this->assertEquals('1234', $controller->getUser()->getId());
    }
}