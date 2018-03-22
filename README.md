## Annotations

annotations injection in php class

```php
<?php
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
```