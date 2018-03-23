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
    /**
     * @inject (C)
     */
    public function setC(C $c)
    {
        $this->c = $c;
    }
}

class B 
{
    public $name = 'Je suis la classe B';
}

class c 
{
    public $name = 'Je suis la classe C';
}

$resolve = (new Annotations(Example::class, ['setB','setC']))->resolve();

var_dump($resolve->b->name); // Je suis la classe B
var_dump($resolve->c->name); // Je suis la classe C
var_dump($resolve->param); // je suis un paramètre
