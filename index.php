<?php
require 'Annotations.php';

class Example
{

    public $b;
    public $param;
    public $c;
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

$annotations = new Annotations(Example::class);
$resolve = $annotations->resolve();

var_dump($resolve->b->name); // Je suis la classe B
var_dump($resolve->param); // je suis un paramètre
