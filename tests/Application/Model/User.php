<?php

namespace Meow\DI\Tests\Application\Model;

class User
{
    protected string $id = '1234';

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}