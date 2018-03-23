<?php

class Annotations
{
    private $pattern = "#@inject+\s*\(([a-zA-Z0-9, ()_].*)\)#";
    private $class;
    private $className;
    private $methods;

    public function __construct($class, $methods = null)
    {
        $this->methods = $methods;
        $this->className = $class;
        $this->class = new ReflectionClass($class);
    }

    private function resolveConstructor() 
    {
        $constructor = $this->class->getConstructor();
        if (!is_null($constructor) && !empty($constructor->getParameters())) {
            $arguments = $this->extractParameters('__construct');
            return new $this->className(...$this->resolveParameters($arguments));
        } else {
            return new $this->className();
        }
    }

    private function resolveMethods()
    {
        $instance = $this->resolveConstructor();
            foreach ($this->methods as $method) {
                $arguments = $this->extractParameters($method);
                $instance->$method(...$this->resolveParameters($arguments));
            }
        return $instance;
    }

    private function extractParameters($method)
    {
        $annotations = $this->class->getMethod($method)->getDocComment();
        preg_match($this->pattern, $annotations, $matches);
        return $arguments = explode(',', $matches[1]);
    }

    private function resolveParameters($arguments)
    {
        foreach ($arguments as $arg) {
            $argument = trim($arg);
            $args[] = class_exists($argument) ? new $argument() : $argument;
        }
        return $args;
    }

    public function resolve()
    {
        if ($this->methods == null) {
            return $this->resolveConstructor();
        } else {
            return $this->resolveMethods();
        }
        
    }
}