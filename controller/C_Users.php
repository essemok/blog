<?php

class C_Users extends C_Base {
    //
    //Вывод всех пользователей
    //
    public function action_index(){
        $this->title .= "::Пользователи";
        $mUsers = M_Users::GetInstance();
        $users = $mUsers->users_all();
        $this->content = $this->Template("view/users.php", array('users' => $users));
    }

    //
    //Вывод конкретного пользователя
    //
    public function action_read(){
        $this->title .= "::Профиль";

        if(isset($_GET['id'])) {
            $mUsers = M_Users::GetInstance();
            $user = $mUsers->Get($_GET['id']);

            if(isset($user['id_user'])) {
                $data = $mUsers->IsOnline($user['id_user']);

                if($data == true) $info = "Online";
                else $info = "Offline";

                $this->content = $this->Template("view/user.php", array('user' => $user, 'info' => $info));
            }
        }
    }

    //
    //Авторизация пользователя
    //
    public function action_login() {
        $this->title .= "::Авторизация";
        $mUsers = M_Users::GetInstance();
        $uid = $mUsers->GetUid();

        //Если пользователь авторизован, возвращаемся на его страничку
        if ($uid !== null) {
            header('location: index.php?c=users&act=read&id=' . $uid);
            exit();
        }

        if($this->isPost()) {
            $data = $mUsers->Login($_POST['login'], $_POST['password'], $_POST['remember']);

            if($data == true) {
                $_SESSION['login'] = $_POST['login']; //Записываем в сессию логин пользователя
                $user = $mUsers->GetByLogin($_SESSION['login']);
                header('location: index.php?c=users&act=read&id=' . $user['id_user']);
                exit();
            } else {
                $msg = "Проверьте правильность введенных данных";
                $this->content = $this->Template("view/login.php", array('login' => $_POST['login'], 'password' => $_POST['password'], 'remember' => $_POST['remember'], 'msg' => $msg));
            }
        } else {
            $_SESSION['sid'] = false;
            $msg = "";
            $this->content = $this->Template("view/login.php", array('login' => "", 'password' => "", 'remember' => "", 'msg' => $msg));
        }
    }

    //
    //Выход пользователя
    //
    public function action_logout() {
        $mUsers = M_Users::GetInstance();
        $mUsers->Logout();
        header('location: index.php');
    }


    //
    //Регистрация пользователя
    //
    public function action_register(){
        $this->title .= '::Регистрация';
        $mUsers = M_Users::GetInstance();

        if($this->isPost()) {
            $data = $mUsers->Register($_POST['login'], $_POST['password'], $_POST['password_confirm'], $_POST['email']);
            if($data == true) {
                header('location: index.php');
                exit();
            } else {
                $msg = "Заполните все поля";

                if($_POST['password'] !== $_POST['password_confirm']) $msg = "Пароли не совпадают";

                if($mUsers->GetByLogin($_POST['login']) == true) $msg = "Пользователь с таким именем уже существует";

                $this->content = $this->Template("view/register.php", array(
                    'login' => $_POST['login'], 'password' => "", 'password_confirm' => "", 'email' => $_POST['email'], 'msg' => $msg
                ));
            }

        } else {
            $msg = "";
            $this->content = $this->Template("view/register.php", array(
                'login' => "", 'password' => "", 'password_confirm' => "", 'email' => "", 'msg' => $msg));
        }
    }

}

?>