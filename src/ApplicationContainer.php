<?php

namespace Meow\DI;

/**
 * Dependency injection container
 */
class ApplicationContainer implements ContainerInterface
{
    /**
     * @var array<object>|array
     */
    protected array $instances = [];

    /**
     * Add service to container
     * define those services in 'Services' array in application configuration
     *
     * @param string $interface
     * @param string|null $object
     */
    public function set(string $interface, ?string $object = null): void
    {
        if ($object == null) {
            $object = $interface;
        }

        $this->instances[$interface] = $object;
    }

    /**
     * This will resolve service which was previous set with set function
     *
     * @param string $interface
     * @param array $parameters
     * @return object
     * @throws \Exception
     */
    public function get(string $interface, array $parameters = []): object
    {
        if (!isset($this->instances[$interface])) {
            $this->set($interface);
        }

        return $this->resolve($this->instances[$interface], $parameters);
    }

    /**
     * Returns new instance of class with resolved dependencies
     *
     * @param class-string<object>|object $object
     * @param array $parameters
     * @return object
     * @throws \ReflectionException
     */
    public function resolve(string|object $object, array $parameters = []): object
    {
        if ($object instanceof \Closure) {
            return $object($this, $parameters);
        }

        $reflector = new \ReflectionClass($object);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$object} is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        /** @var array<\ReflectionParameter> $parameters */
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve dependencies
     *
     * @param \ReflectionParameter[]|array $parameters
     * @return array
     * @throws \Exception
     */
    public function getDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            // $dependency = $parameter->getClass();
            // todo php stan returning this as an error check if it bug in php stan
            /** @var \ReflectionNamedType|null $reflectionNamedType */
            $reflectionNamedType = $parameter->getType();

            if ($reflectionNamedType && !$reflectionNamedType->isBuiltin()) {
                /** @var class-string<object> $typeName */
                $typeName = $reflectionNamedType->getName();
                $dependency = new \ReflectionClass($typeName);
            } else {
                $dependency = null;
            }

            if ($dependency == null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Can not resolve class dependency {$parameter->getName()}");
                }
            } else {
                $dependencies[] = $this->get($dependency->getName());
            }
        }

        return $dependencies;
    }
}
