<?php
namespace App\Controllers;

use App\Core\View;
use Intervention\Image\ImageManager;
class MainController
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function getImageService()
    {
        return new ImageManager(array('driver' => 'gd'));
    }

    public function user()
    {
        $user = null;

        if (!empty($_SESSION["user"])) {
            $user = $_SESSION["user"];
        }

        return $user;
    }

    public function pageNotFound()
    {
        $this->view->render('404',[]);
    }
}
