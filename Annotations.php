<?php
/**
 * Class Annotations
 * 
 * Injection des dépendances par annotations
 */
class Annotations
{
    /**
     * Pattern pour extraire les arguments
     *
     * @var string
     */
    private $pattern = "#@inject+\s*\(([a-zA-Z0-9,_\-].*)\)#";
    /**
     * instance de Reflection class
     *
     * @var object
     */
    private $reflection;
    /**
     * Nom de la classe 
     *
     * @var string
     */
    private $className;
    /**
     * Liste des méthodes
     *
     * @var array
     */
    private $methods;
    /**
     * constructeur
     *
     * @param string $class le nom de la classe
     * @param array $methods liste des méthodes
     */
    public function __construct($class, $methods = null) 
    {
        $this->methods = $methods;
        $this->className = $class;
        $this->reflection = new ReflectionClass($class);
    }
    /**
     * Annotations injection du constructeur si celui ci est présent
     *
     * @return object
     */
    private function resolveConstructor() 
    {
        $constructor = $this->reflection->getConstructor();
        if (!is_null($constructor) && !empty($constructor->getParameters())) {
            $arguments = $this->extractParameters('__construct');
            return new $this->className(...$this->resolveParameters($arguments));
        } else {
            return new $this->className();
        }
    }
    /**
     * Annotations injection des méthodes si définit dans $methods du constructeur
     *
     * @return object
     */
    private function resolveMethods()
    {
        $instance = $this->resolveConstructor();
            foreach ($this->methods as $method) {
                $arguments = $this->extractParameters($method);
                $instance->$method(...$this->resolveParameters($arguments));
            }
        return $instance;
    }
    /**
    * extraction des paramètres annotés
    *
    * @param string $method
    * @return array
    */
    private function extractParameters($method) :array
    {
        $annotations = $this->reflection->getMethod($method)->getDocComment();
        preg_match($this->pattern, $annotations, $matches);
        return $arguments = explode(',', $matches[1]);
    }
    /**
     * Résolution des dépendances
     *
     * @param array $arguments
     * @return array
     */
    private function resolveParameters($arguments) :array
    {
        foreach ($arguments as $arg) {
            $argument = trim($arg);
            $args[] = class_exists($argument) ? new $argument() : $argument;
        }
        return $args;
    }
    /**
     * Retourne l'objet et ses dépendances
     *
     * @return object
     */
    public function resolve()
    {
        if ($this->methods == null) {
            return $this->resolveConstructor();
        } else {
            return $this->resolveMethods();
        }
    }
}
