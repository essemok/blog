<?php

class M_Comments {
    //
    // Вывод комментариев
    //
    public static function show_comments($id_article)
    {
        $sql = "SELECT * FROM comments WHERE id_article = $id_article ORDER BY id_comment DESC";
        $db = M_Mysql::GetInstance();
        $rows = $db->Select($sql);
        return $rows;
    }

    //
    // Добавление комментария
    //
    public static function comments_new($id_article, $name, $c_text)
    {
        // Удаляем лишние пробелы
        $name = trim($name);
        $c_text = trim($c_text);

        // Проверка
        if ($name == '' || $c_text == '')
            return false;

        // Запрос
        $table = "comments";
        $object = ['id_article'=> $id_article, 'name' => $name, 'c_text' => $c_text];
        $db = M_Mysql::GetInstance();
        $db->Insert($table, $object);
        return true;
    }

    //
    // Редактирование комментария
    //
    public static function comments_edit($id_comment, $name, $c_text)
    {
        // Удаляем лишние пробелы
        $name = trim($name);
        $c_text = trim($c_text);

        // Проверка
        if ($name == '' || $c_text == '')
            return false;

        // Запрос
        $table = "comments";
        $object = ['name' => $name, 'c_text' => $c_text];
        $where = "id_comment = $id_comment";
        $db = M_Mysql::GetInstance();
        $db->Update($table,  $object, $where);
        return true;
    }

    //
    // Удаление комментария
    //
    public static function comments_delete($id_comment)
    {
        $table = "comments";
        $where = "id_comment = $id_comment";
        $db = M_Mysql::GetInstance();
        $db->Delete($table, $where);
        return true;
    }
}

?>