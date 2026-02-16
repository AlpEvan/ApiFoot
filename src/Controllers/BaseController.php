<?php

namespace EvanAlpst\ApiFoot\Controllers;

use EvanAlpst\ApiFoot\Services\UserService;
use Slim\Views\PhpRenderer;

abstract class BaseController
{
    /**
     * @var PhpRenderer
     */
    protected PhpRenderer $view;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->view = new PhpRenderer(__DIR__ . '/../../views', [
            'title' => 'Api Foot',
            'withMenu' => true,
        ]);

        $this->view->setLayout("layout.php");
    }
}
