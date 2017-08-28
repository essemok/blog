<?php
session_start();
$mUsers = M_Users::GetInstance();
$mUsers->ClearSessions();

abstract class C_Base extends C_Controller {
    protected $title;
    protected $content;

    public function before() {
        $this->title = "Блог";
        $this->content = "";
    }

    public function render() {
        $page = $this->Template("view/layout.php", array('title' => $this->title, 'content' => $this->content));
        echo $page;
    }

    public function __call($name, $params) {
        $content = "Ошибка 404!";
        $this->content = $this->Template("view/error.php", array('content' => $content));
    }
}

?>