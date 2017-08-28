<?php
//
// Менеджер пользователей
//
class M_Users
{
    private static $instance;	// экземпляр класса
    private $msql;				// драйвер БД
    private $sid;				// идентификатор текущей сессии
    private $uid;				// идентификатор текущего пользователя


    public static function GetInstance()
    {
        if (self::$instance == null)
            self::$instance = new M_Users();

        return self::$instance;
    }

    //
    // Конструктор
    //
    public function __construct()
    {
        $this->msql = M_Mysql::GetInstance();
        $this->sid = null;
        $this->uid = null;
    }

    //
    // Очистка неиспользуемых сессий
    //
    public function ClearSessions()
    {
        $min = date('Y-m-d H:i:s', time() - 60 * 20);
        $t = "time_last < '%s'";
        $where = sprintf($t, $min);
        $this->msql->Delete('sessions', $where);
    }

    //
    // Авторизация
    // $login 		- логин
    // $password 	- пароль
    // $remember 	- нужно ли запомнить в куках
    // результат	- true или false
    //

    public function Login($login, $password, $remember = true)
    {
        // вытаскиваем пользователя из БД
        $user = $this->GetByLogin($login);

        if ($user == null)
            return false;

        $id_user = $user['id_user'];


        // проверяем пароль
        if ($user['password'] != md5($password))
            return false;

        // запоминаем имя и md5(пароль)
        if ($remember)
        {
            $expire = time() + 3600 * 24 * 10;
            setcookie('login', $login, $expire);
            setcookie('password', md5($password), $expire);
        }

        // открываем сессию и запоминаем SID
        $this->sid = $this->OpenSession($id_user);

        return true;
    }

    //
    // Регистрация
    // $login 		- логин
    // $password 	- пароль
    // $email 	  - адрес электронной почты
    //

    public function Register($login, $password, $password_confirm, $email)
    {
        // Удаляем лишние пробелы
        $login = trim($login);
        $password = trim($password);
        $email = trim($email);

        $mUsers = M_Users::GetInstance();
        $check_login = $mUsers->GetByLogin($login);

        // Проверка
        if ($login == '' || $password == '' || $email == '')
            return false;

        if($password !== $password_confirm)
            return false;

        if($check_login == true)
            return false;

        // Запрос
        $table = "users";
        $object = ['login' => $login, 'password' => md5($password), 'email' => $email];
        $this->msql->Insert($table, $object);
        return true;

    }

    //
    // Выход
    //
    public function Logout()
    {
        setcookie('login', '', time() - 1);
        setcookie('password', '', time() - 1);
        unset($_COOKIE['login']);
        unset($_COOKIE['password']);
        unset($_SESSION['sid']);
        unset($_SESSION['login']);
        $this->sid = null;
        $this->uid = null;
    }

    //
    // Список всех пользователей
    //
    public function users_all()
    {
        $sql = "SELECT * FROM users ORDER BY id_user DESC";
        $rows = $this->msql->Select($sql);
        return $rows;
    }

    //
    // Получение пользователя
    // $id_user		- если не указан, брать текущего
    // результат	- объект пользователя
    //
    public function Get($id_user = null)
    {
        // Если id_user не указан, берем его по текущей сессии
        if ($id_user == null)
            $id_user = $this->GetUid();

        if ($id_user == null)
            return null;

        // Возвращаем пользователя по id_user
        $t = "SELECT * FROM users WHERE id_user = '%d'";
        $query = sprintf($t, $id_user);
        $rows = $this->msql->Select($query);
        $str = array();
        for($i = 0; $i < count($rows); $i++) {
            $str = $rows[$i];
        }
        return $str;
    }

    //
    // Получаем пользователя по логину
    //
    public function GetByLogin($login)
    {
        $t = "SELECT * FROM users WHERE login = '%s'";
        $query = sprintf($t, $login);
        $rows = $this->msql->Select($query);
        $user = array();
        for($i = 0; $i < count($rows); $i++) {
            $user = $rows[$i];
        }
        return $user;
    }

    //
    // Проверяем наличие привилегий
    // $priv 		- имя привилегии
    // $id_user		- если не указан, значит, для текущего
    // результат	- true или false
    //
    public function Can($priv, $id_user = null)
    {
        if ($id_user == null)
            $id_user = $this->GetUid();
        if ($id_user == null)
            return false;
        // Возвращаем кол-во выбранных записей по id_user.
        $query = "SELECT * FROM privs2roles LEFT JOIN users ON privs2roles.id_role = users.id_role 
                  LEFT JOIN privs ON privs2roles.id_priv = privs.id_priv 
                  WHERE users.id_user = $id_user AND privs.name = '$priv'";
        $result = $this->msql->Select($query);
        return (count($result) > 0);
    }

    //
    // Проверка активности пользователя
    // $id_user		- идентификатор
    // результат	- true если online
    //
    public function IsOnline($id_user) {
        $query = "SELECT * FROM sessions WHERE id_user = $id_user";
        $rows = $this->msql->Select($query);
        return (count($rows) > 0);
    }

    //
    // Получение id текущего пользователя
    // результат	- UID
    //
    public function GetUid()
    {
        // Проверяем кэш
        if ($this->uid != null)
            return $this->uid;

        // Берем по текущей сессии
        $sid = $this->GetSid();

        if ($sid == null)
            return null;

        $t = "SELECT id_user FROM sessions WHERE sid = '%s'";
        $query = sprintf($t, $sid);
        $result = $this->msql->Select($query);

        // Если сессию не нашли - значит пользователь не авторизован
        if (count($result) == 0)
            return null;

        // Если нашли - запомним ее
        $this->uid = $result[0]['id_user'];
        return $this->uid;
    }

    //
    // Функция возвращает идентификатор текущей сессии
    // результат	- SID
    //
    private function GetSid()
    {
        // Проверяем кэш
        if ($this->sid != null)
            return $this->sid;

        // Ищем SID в сессии.
        $sid = $_SESSION['sid'];

        // Пропробуем обновить time_last в базе
        // Проверяем, есть ли сессия там
        if ($sid != null)
        {
            $session = array();
            $session['time_last'] = date('Y-m-d H:i:s');
            $t = "sid = '%s'";
            $where = sprintf($t, $sid);
            $affected_rows = $this->msql->Update('sessions', $session, $where);

            if ($affected_rows == 0)
            {
                $t = "SELECT count(*) FROM sessions WHERE sid = '%s'";
                $query = sprintf($t, $sid);
                $result = $this->msql->Select($query);

                if ($result[0]['count(*)'] == 0)
                    $sid = null;
            }
        }

        // Нет сессии? Ищем логин и md5(пароль) в куках
        // Пробуем переподключиться
        if ($sid == null && isset($_COOKIE['login']))
        {
            $user = $this->GetByLogin($_COOKIE['login']);

            if ($user != null && $user['password'] == $_COOKIE['password'])
                $sid = $this->OpenSession($user['id_user']);
        }

        // Запоминаем в кэш
        if ($sid != null)
            $this->sid = $sid;

        // Возвращаем SID
        return $sid;
    }

    //
    // Открываем новую сессию
    //
    private function OpenSession($id_user)
    {
        // генерируем SID
        $sid = $this->GenerateStr(10);

        // вставляем SID в БД
        $now = date('Y-m-d H:i:s');
        $session = array();
        $session['id_user'] = $id_user;
        $session['sid'] = $sid;
        $session['time_start'] = $now;
        $session['time_last'] = $now;
        $this->msql->Insert('sessions', $session);

        // регистрируем сессию в PHP сессии
        $_SESSION['sid'] = $sid;

        // возвращаем SID
        return $sid;
    }

    //
    // Генерация случайной последовательности
    // $length 		- ее длина
    //
    private function GenerateStr($length = 10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;

        while (strlen($code) < $length)
            $code .= $chars[mt_rand(0, $clen)];

        return $code;
    }
}
