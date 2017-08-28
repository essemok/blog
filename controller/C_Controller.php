<?php

abstract class C_Controller {
    protected abstract function before();

    protected abstract function render();

    public function Request($action)
    {
        $this->before();
        $this->$action();
        $this->render();
    }

    protected function IsGet()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    protected function IsPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    protected function Template($file, $params = array()) {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include "$file";
        return ob_get_clean();
    }
}

?>