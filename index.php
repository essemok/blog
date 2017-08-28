<?php
function classes_autoload($class) {
    //Подгружаем классы
    $file = 'controller/'.$class.'.php';
    if(!is_file($file)) {
        $file = 'model/'.$class.'.php';
        if (!is_file($file))
            return;
    }
    include_once($file);
}

spl_autoload_register('classes_autoload');

$action = 'action_';
$action .= (isset($_GET['act'])) ? $_GET['act'] : 'index';

if(isset($_GET['c'])) {
    switch ($_GET['c']) {
        case 'articles':
            $controller = new C_Articles();
            break;
        case 'users':
            $controller = new C_Users();
            break;
        default:
            $controller = new C_Articles();
    }
} else $controller = new C_Articles();

$controller->Request($action);