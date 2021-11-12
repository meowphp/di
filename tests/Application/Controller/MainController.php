<?php

namespace Meow\DI\Tests\Application\Controller;

use Meow\DI\Tests\Application\Model\User;
use Meow\DI\Tests\Application\Services\BaseServiceInterface;

class MainController
{
    protected User $user;

    protected BaseServiceInterface $service;

    public function __construct(BaseServiceInterface $service, User $user)
    {
        $this->user = $user;
        $this->service = $service;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return BaseServiceInterface
     */
    public function getService(): BaseServiceInterface
    {
        return $this->service;
    }
}
