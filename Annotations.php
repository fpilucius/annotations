<?php

class Annotations
{
    private $class;

    private $className;

    public function __construct($class)
    {
        $this->className = $class;
        $this->class = new ReflectionClass($class);
    }

    private function resolveConstructor() 
    {
        $annotations = $this->class->getMethod('__construct')->getDocComment();

        $pattern = "#@inject+\s*\(([a-zA-Z0-9, ()_].*)\)#";

        preg_match($pattern, $annotations, $matches);

        $arguments = explode(',', $matches[1]);

        foreach ($arguments as $arg) {
            $argument = trim($arg);
            $args[] = class_exists($argument) ? new $argument() : $argument;
        }
        return new $this->className(...$args);
    }

    public function resolve()
    {
        return $this->resolveConstructor();
    }
}