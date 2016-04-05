<?php

namespace Mchekin\MVCBoiler\Views;

use \Exception;

class View
{
    /**
     * @var string
     */
    protected $viewsDirectory;

    /**
     * View constructor.
     * @param $viewsDirectory
     */
    public function __construct($viewsDirectory)
    {
        $this->viewsDirectory = $viewsDirectory;
    }

    /**
     * Rendering the view from a path
     *
     * @param $viewPath
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function render($viewPath, $data = [])
    {
        $filePath = $this->viewsDirectory.'/'.$viewPath;

        // check if the view file exists and readable
        if (!is_readable($filePath)) {
            throw new Exception('View '.$viewPath.' does not exists');
        }

        // converting data to variables for use in the view file
        extract($data);

        // buffering view data and returning it
        ob_start();

//        var_dump($filePath);

        require $filePath;

        return ob_get_clean();
    }
}
