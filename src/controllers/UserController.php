<?php

namespace App\Controllers;

use App\Models\User;
use Twig_Loader_Filesystem;
use Twig_Environment;

class UserController extends MainController
{
    public function index()
    {
        $currentPage = $_SERVER['REQUEST_URI'];
        if($currentPage == "/" || !empty($_GET['error'])){$activePage = "class=active"; }else{$activePage = null; }
        // Вывод сообщения если пользователя нет в базе данных
        if (!empty($_GET['error'])) {
            $array = "Такого пользователя нет в базе данных, зарегистрируйтесь";
        } else {
            $array = null;
        }

        if (!empty($this->user())) {
            header("Location: /filelist.php");
        }

        $loader = new Twig_Loader_Filesystem('src/views');
        $twig = new Twig_Environment($loader);
        echo $twig->render('home.php',  [
            'pageData' => [
                'indexPage'   => $activePage,
                'error'   => $array
            ]]);


        //$this->view->render('home', ['error' => $array], $currentPage);
    }

    public function edit($userId)
    {
        $userModel = new User();
        $user = $userModel->getUserById($userId);

        $loader = new Twig_Loader_Filesystem('src/views');
        $twig = new Twig_Environment($loader);
        echo $twig->render('edit.php',  [
            'pageData' => [
                'user'   => $user
            ]]);
        //$this->view->render('edit', ['user' => $user]);
    }

    public function update(array $data)
    {
        if (empty($data['login']) || empty($data['password'])) {
            echo "Ошибка, не указан логин или пароль";
            return 0;
        }
        if ($data['password'] != $data['confirm_password']) {
            echo "Пароли не совпадают";
            return 0;
        }

        if (empty($data['name']) || empty($data['desc']) || empty($data['age']) || empty($data['Email_Parse'])) {
            echo "Не указано имя, возраст, email или описание";
            return 0;
        }

        $data['password'] = md5($_POST['password']);
        $data['confirm_password'] = md5($_POST['confirm_password']);
        // обновление базы
        if (file_exists($_FILES['userfile']['tmp_name'])) {
            $uploaddir = realpath(__DIR__ . "/../../upload");
            $uploadfile = $uploaddir . DIRECTORY_SEPARATOR . md5($_POST['login']);
            $status = move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
            $this->getImageService()->make($uploadfile)->resize(100, 100)->save();
            if ($status) {
                $replace = "http://" . $_SERVER['SERVER_NAME'] . ":81/upload/" . md5($_POST['login']);
                $data['photo'] = $replace;
                $data['photo_name'] = md5($_POST['login']);
            }
        }else{
            $data['photo_name'] = null;
            $data['photo'] = null;
        }
        
        $userModel = new User();
        $user = $userModel->UpdateUser($data['user_id'], $data);

        if ($user) {
            header("Location: /list.php");
        } else {
            echo "Данные пользователя не были обновлены";
        }

        // редирект на список пользователей
    }

    public function checkAuth()
    {
        // Проверки на пустые поля
        if (empty($_POST['login']) || empty($_POST['password'])) {
            echo "Ошибка, не указан логин или пароль";
            return 0;
        }
        $model = new User();
        $data = ['login' => trim(strtoupper($_POST['login'])), 'password' => md5($_POST['password']),];

        $user = $model->auth($data['login'], $data['password']);

        if ($user === null) {
            header("Location: /?error=1");
            return 0;
        }
        $_SESSION['user'] = $data['login'];
        header("Location: /list.php");
        return 0;
    }

    public function reg()
    {
        $currentPage = $_SERVER['REQUEST_URI'];
        if($currentPage == "/reg.php"){$activePage = "class=active"; }else{$activePage = null; }
        if (!empty($_SESSION['user'])) {
            header("Location: /filelist.php");
        }
        $loader = new Twig_Loader_Filesystem('src/views');
        $twig = new Twig_Environment($loader); //, array('cache' => 'src/views')

        echo $twig->render('reg.php',[
            'pageData' => [
                'regPage'   => $activePage
            ]]);
        //$this->view->render('reg', [], $currentPage);
    }


    public function list()
    {
        if ($this->user() === null) {
            header("Location: /");
        }

        $currentPage = $_SERVER['REQUEST_URI'];
        if($currentPage == "/list.php"){$activePage = "class=active"; }else{$activePage = null; }
        // Обработка из базы данных в массив
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $loader = new Twig_Loader_Filesystem('src/views');
        $twig = new Twig_Environment($loader);
        //var_dump($users[0]['AGE']);
        /*$users[0]['AGE']." Несо
        foreach ($users as $key => $user) {
            $users[$key]['AGE'] = $user['AGE'] . (($users[$key]['AGE'] > 18) ? " Совершеннолетний" : " Несовершеннолетний");
        }*//*
        if($users[0]['AGE'] > 18)
        {
            $users[0]['AGE'] += $users[0]['AGE']." Совершеннолетний";
        }else{
            $users[0]['AGE'] = вершеннолетний";
        }*/


            echo $twig->render('list.php',[
                'pageData' => [
                    'listPage'   => $activePage,
                    'modelUsers' => $users
                ]]);


        // Рендер страницы и передача туда массива из базы данных
        //$this->view->render('list', ['users' => $users], $currentPage);
    }

    public function filelist()
    {
        if (empty($this->user())) {
            header("Location: /");
            return 0;
        }

        $currentPage = $_SERVER['REQUEST_URI'];
        if($currentPage == "/filelist.php"){$activePage = "class=active"; }else{$activePage = null; }
        $modelShowFiles = new User();
        $modelfiles = $modelShowFiles->getFiles();
        $loader = new Twig_Loader_Filesystem('src/views');
        $twig = new Twig_Environment($loader); //, array('cache' => 'src/views')

        echo $twig->render('filelist.php',[
            'pageData' => [
                'filePage'   => $activePage,
                'modelFiles' => $modelfiles
            ]]);

        //$this->view->render('filelist', ['modelfiles' => $modelfiles], $currentPage);
    }

    public function removeImage()
    {
        $userModel = new User();
        $userModel->removePic($_GET['remove_userpic']);
        //var_dump($userModel);
        //exit();
        header("Location: /filelist.php");
    }

    public function removeUser()
    {
        $userModel = new User();
        $remove = $userModel->removeUser($_GET['remove_user_id']);
        if ($remove == true) {
            header("Location: /list.php");
        } else {
            echo "Пользователь не был удален";
        }
    }


    public function registration()
    {
        // Проверки на пустые поля
        if (empty($_POST['login']) || empty($_POST['password'])) {
            echo "Ошибка, не указан логин или пароль";
            return 0;
        }
        if ($_POST['password'] != $_POST['confirm_password']) {
            echo "Пароли не совпадают";
            return 0;
        }

        if (empty($_POST['name']) || empty($_POST['desc']) || empty($_POST['age'])) {
            echo "Не указано имя, возраст или описание";
            return 0;
        }


        $login = trim(strtoupper($_POST['login']));
        $model = new User();
        $user = $model->getUserByLogin($login);

        if ($user !== null) {
            echo "Пользователь с логином " . $login . " существует";
            return 0;
        }

        $data = ['login' => $login, 'password' => md5($_POST['password']), 'name' => $_POST['name'], 'age' => $_POST['age'], 'desc' => $_POST['desc'], 'photo' => null, 'photo_name' => null];

        if (file_exists($_FILES['userfile']['tmp_name'])) {
            $uploaddir = realpath(__DIR__ . "/../../upload");
            $uploadfile = $uploaddir . DIRECTORY_SEPARATOR . md5($_POST['login']);
            $status = move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
            // Ресайз загружаемой фотки и сохранение
            $this->getImageService()->make($uploadfile)->resize(100, 100)->save();
            if ($status) {
                $replace = "http://" . $_SERVER['SERVER_NAME'] . ":81/upload/" . md5($_POST['login']);
                $data['photo'] = $replace;
                $data['photo_name'] = md5($_POST['login']);

                //$data['photo'] = $uploadfile;
            }
        }

        $model->CreateUser($data);
        $user = $model->getUserByLogin($login);

        if ($user === null) {
            echo "Не удалось записать в БД";
            return 0;
        }

        $_SESSION['user'] = $login;
        header("Location: /filelist.php");
    }
}
