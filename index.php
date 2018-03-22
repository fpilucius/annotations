<?php
class Example
{

    public $b;
    public $param;
    /**
     * @inject (B, je suis un paramètre)
     */
    public function __construct (B $b, $param) 
    {
        $this->b = $b;
        $this->param = $param;
    }
}

class B 
{
    public $name = 'Je suis la classe B';
}

$comment_string = (new ReflectionClass('Example'))->getMethod('__construct')->getDocComment();

//define the regular expression pattern to use for string matching
$pattern = "#@inject+\s*\(([a-zA-Z0-9, ()_].*)\)#";

//perform the regular expression on the string provided
preg_match($pattern, $comment_string, $matches);

$arguments = explode(',', $matches[1]);

foreach ($arguments as $arg) {
    $argument = trim($arg);
    $args[] = class_exists($argument) ? new $argument() : $argument;
}

$class = "Example";
$annotations = new $class(...$args);
var_dump($annotations->b->name); // Je suis la classe B
var_dump($annotations->param); // je suis un paramètre
