<?php

namespace Mchekin\MVCBoiler;

use App\CommentsSystem\Entity\CommentRepository;
use Exception;
use Mchekin\MVCBoiler\Database\DatabaseHandlerInterface;
use Mchekin\MVCBoiler\Http\RequestInterface;
use Mchekin\MVCBoiler\Http\ResponseInterface;
use Mchekin\MVCBoiler\Http\Router;
use Mchekin\MVCBoiler\Views\View;

class Container
{
    /**
     * @var Container
     */
    static protected $global;

    /**
     * @var array
     */
    protected $bound = [];

    /**
     * @var string
     */
    protected $rootDirectory;

    /**
     * Container constructor.
     * @param array $components
     * @param $rootDirectory
     */
    public function __construct(array $components, $rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;

        foreach( $components as $key => $component) {
            $this->bind($key, $component);
        }

        static::setGlobal($this);
    }

    /**
     * @return Container
     */
    public static function getGlobal()
    {
        return self::$global;
    }

    /**
     * @param Container $global
     */
    public static function setGlobal(Container $global)
    {
        self::$global = $global;
    }

    /**
     * @param $table
     * @param DatabaseHandlerInterface $dbHandler
     * @param array $migrationFiles
     * @return $this
     */
    public function initTable($table, DatabaseHandlerInterface $dbHandler, array $migrationFiles)
    {
        echo "Checking if table ".$table." exists " . PHP_EOL;

        if (!$dbHandler->collectionExists($table)) {

            echo 'Table '.$table.' does not exist' . PHP_EOL;
            echo 'Creating table ' . $table . PHP_EOL;

            foreach ($migrationFiles as $file) {
                echo '>' . $file . PHP_EOL;
                $dbHandler->rawQuery(file_get_contents($file));
            }

            echo 'Table ' . $table . ' created' . PHP_EOL;
        } else {
            echo 'Table ' . $table . ' exists' . PHP_EOL;
        }

        return $this;
    }

    /**
     * @param $key
     * @param $component
     */
    public function bind($key, $component)
    {
        $this->bound[$key] = $component;
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function resolve($key)
    {
        if (!isset($this->bound[$key])) {
            throw new Exception('No key "'.$key.'" to be resolved');
        }

       return  $this->bound[$key];
    }

    /**
     *  Running the application
     */
    public function run()
    {
        // resolving the request uri path into one of the configured routes
        list($controllerName, $actionName, $parameters) = $this->router()->resolve($this->request());

        // creating the resolved controller instance
        $controller = new $controllerName($this);

        // calling the resolved action on the controller
        call_user_func_array(array($controller, $actionName), $parameters);
    }

    /**
     * @return RequestInterface
     * @throws Exception
     */
    public function request()
    {
        return $this->resolve('request');
    }

    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function response()
    {
        return $this->resolve('response');
    }

    /**
     * @return CommentRepository
     * @throws Exception
     */
    public function commentRepo()
    {
        return $this->resolve('commentRepo');
    }

    /**
     * @return View
     * @throws Exception
     */
    public function view()
    {
        return $this->resolve('view');
    }

    /**
     * @return Router
     * @throws Exception
     */
    public function router()
    {
        return $this->resolve('router');
    }
}
