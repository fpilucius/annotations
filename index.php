<?php
require 'Annotations.php';

class Example
{

    public $b;
    public $param;
    public $c;
    /**
     * @inject (je suis un paramètre)
     */
    public function __construct ($param) 
    {
        $this->param = $param;
    }
    /**
     * @inject (B)
     */
    public function setB(B $b)
    {
        $this->b = $b;
    }
}

class B 
{
    public $name = 'Je suis la classe B';
}

$annotations = new Annotations(Example::class, ['setB']);
$resolve = $annotations->resolve();

var_dump($resolve->b->name); // Je suis la classe B
var_dump($resolve->param); // je suis un paramètre
