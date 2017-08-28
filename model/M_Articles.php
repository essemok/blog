<?php

class M_Articles {
    private static $instance;

    public static function GetInstance(){
        if(self::$instance == null)
            self::$instance = new M_Articles();

        return self::$instance;
    }

    //
    // Список всех статей
    //
    public function articles_all()
    {
        $sql = "SELECT * FROM articles ORDER BY id_article DESC";
        $db = M_Mysql::GetInstance();
        $rows = $db->Select($sql);
        return $rows;
    }

    //
    // Конкретная статья
    //
    public function articles_get($id_article)
    {
        $sql = "SELECT * FROM articles WHERE id_article = $id_article";
        $db = M_Mysql::GetInstance();
        $rows = $db->Select($sql);
        $str = array();
        for($i = 0; $i < count($rows); $i++) {
            $str = $rows[$i];
        }
        return $str;
    }

    //
    // Добавление статьи
    //
    public function articles_new($title, $content)
    {
        // Удаляем лишние пробелы
        $title = trim($title);
        $content = trim($content);

        // Проверка
        if ($title == '' || $content == '')
            return false;

        // Запрос
        $table = "articles";
        $object = ['title' => $title, 'content' => $content];
        $db = M_Mysql::GetInstance();
        $db->Insert($table, $object);
        return true;
    }

    //
    // Изменение статьи
    //
    public function articles_edit($id_article, $title, $content)
    {
        //Удаляем лишние пробелы
        $title = trim($title);
        $content = trim($content);

        // Проверка
        if ($title == '' || $content == '')
            return false;

        // Запрос
        $table = "articles";
        $object = ['title' => $title, 'content' => $content];
        $where = "id_article = $id_article";
        $db = M_Mysql::GetInstance();
        $db->Update($table,  $object, $where);
        return true;
    }

    //
    // Удаление статьи
    //
    public function articles_delete($id_article)
    {
        $table = "articles";
        $where = "id_article = $id_article";
        $db = M_Mysql::GetInstance();
        $db->Delete($table, $where);
        return true;
    }

    //
    // Короткое описание статьи
    //
    public static function articles_intro($text, $n = 200)
    {
        $length = mb_strlen($text); // возвращает длину строки

        if ($length > $n) {
            $text = mb_substr($text, 0, $n); // обрежем текст на определённое количество символов
            $text = rtrim($text, "!,.-"); // убедимся, что текст не заканчивается восклицательным знаком, запятой, точкой или тире
            $text = mb_substr($text, 0, strrpos($text, ' ')); // находим последний пробел, устраняем его и ставим троеточие
            return $text."… ";
        }
        else
            return $text;
    }

}

?>