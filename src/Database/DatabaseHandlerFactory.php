<?php

namespace Mchekin\MVCBoiler\Database;

use Exception;

class DatabaseHandlerFactory
{
    /**
     * @var array
     */
    protected $handlers =[];

    /**
     * @var array
     */
    protected $handlerClasses = [];

    public function __construct(array $handlerClasses, array $databaseConfigs)
    {
        $this->handlerClasses = $handlerClasses;

        foreach ($databaseConfigs as $name => $config) {
            $this->register($name, $config);
        }
    }

    /**
     * @param $name
     * @param $config
     * @return $this
     * @throws Exception
     */
    public function register($name, $config)
    {
        // check if no handler has been define for this database type
        if (!isset($this->handlerClasses[$name])) {
            throw new Exception('No database handler class has been defined for the database of type "'.$name.'"', 500);
        }

        $handlerClass = $this->handlerClasses[$name];

        // check if handler class exists
        if (!class_exists($handlerClass)) {
            throw new Exception('Database handler class "'.$handlerClass.'" does not exist', 500);
        }

        // check if the handler implements the DatabaseInterface contract
        if (!is_a($handlerClass, DatabaseHandlerInterface::class, true) ) {
            throw new Exception('Database handler class "'.$handlerClass.'" does not implement the right contract', 500);
        }

        // instantiate the handler
        $this->handlers[$name] = new $handlerClass($config);

        return $this;
    }

    /**
     * @param $name
     * @return DatabaseHandlerInterface
     * @throws Exception
     */
    public function resolve($name)
    {
        // check if the handlers has been registered
        if (empty($this->handlers[$name])) {
            throw new Exception('No registered handler of type "'. $name .'""', 500);
        }

        return $this->handlers[$name];
    }
}
