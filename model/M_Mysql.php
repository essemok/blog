<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "newBD");

class M_Mysql{

    private static $instance;

    private $link;
    private function __construct(){
        $this->link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME)
        or die("Error " . mysqli_error($this->link));
    }

    public static function GetInstance(){
        if(self::$instance == null)
            self::$instance = new M_Mysql();

        return self::$instance;
    }

    //
    // Выборка записей из БД
    //
    public function Select($sql){
        $result = mysqli_query($this->link, $sql);

        if(!$result)
            die(mysqli_error($this->link));

        $count = mysqli_num_rows($result);
        $rows = array();
        for($i=0;$i<$count;$i++){
            $rows[] = mysqli_fetch_assoc($result);
        }

        return $rows;
    }

    //
    // Добавление записей в БД
    //
    public function Insert($table, $object){
        $columns = array();
        $values = array();

        $table = mysqli_real_escape_string($this->link,$table);

        foreach ($object as $key => $value) {
            $key = mysqli_real_escape_string($this->link,$key);
            $columns[] = $key;

            if($value == NULL){
                $values[] = "NULL";
            } else {
                $value = mysqli_real_escape_string($this->link,$value);
                $values[] = "'$value'";
            }
        }

        $columns_s = implode(",", $columns);
        $values_s = implode(",", $values);

        $sql = "INSERT INTO $table ($columns_s) VALUES($values_s)";
        $result = mysqli_query($this->link, $sql);
        if(!$result)
            die(mysqli_error($this->link));

        return mysqli_insert_id($this->link);
    }

    //
    // Изменение записей в БД
    //
    public function Update($table, $object, $where){
        $sets = array();

        foreach ($object as $key => $value) {
            $key = mysqli_real_escape_string($this->link,$key);

            if($value == NULL){
                $sets[] = "$key=NULL";
            } else {
                $value = mysqli_real_escape_string($this->link,$value);
                $sets[] = "$key='$value'";
            }
        }

        $sets_s = implode(", ", $sets);
        $sql = sprintf("UPDATE %s SET %s WHERE %s", $table, $sets_s, $where);
        $result = mysqli_query($this->link, $sql);

        if(!$result)
            die(mysqli_error($this->link));

        return mysqli_affected_rows($this->link);
    }

    //
    // Удаление записей из БД
    //
    public function Delete($table, $where){
        $sql = sprintf("DELETE FROM %s WHERE %s", $table, $where);
        $result = mysqli_query($this->link, $sql);

        if(!$result)
            die(mysqli_error($this->link));

        return mysqli_affected_rows($this->link);
    }
}

?>