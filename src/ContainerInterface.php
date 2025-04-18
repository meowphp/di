<?php

namespace Meow\DI;

interface ContainerInterface
{
    public function resolve(string|object $object, array $parameters = []): object;
}
