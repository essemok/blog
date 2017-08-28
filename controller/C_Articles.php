<?php
class C_Articles extends C_Base {
    //
    //Вывод списка всех статей
    //
    public function action_index(){
        $this->title .= "::Список";
        $mArticles = M_Articles::GetInstance();
        $articles = $mArticles->articles_all();
        $this->content = $this->Template("view/index.php", array('articles' => $articles));
    }

    //
    //Вывод конкретной статьи
    //
    public function action_read(){
        $this->title .= "::Чтение";

        //Проверка id и вывод статьи
        if(isset($_GET['id'])) {
            $mArticles = M_Articles::GetInstance();
            $article = $mArticles->articles_get($_GET['id']);

            if(isset($article['id_article'])) {

                //Получаем комментарии к статье
                $comments = M_Comments::show_comments($_GET['id']);
                $comments_area = $this->Template("view/comments.php", array('comments' => $comments));

                //Удаление комментариев
                if(isset($_POST['c_delete'])) {
                    $data = M_Comments::comments_delete($_POST['id_comment']);
                    if($data == true) header('location: index.php?c=articles&act=read&id=' . $_GET['id']);
                }

                //Форма комментирования и отправка данных
                if($this->isPost()) {
                    //Проверяем авторизован ли пользователь и выполняем подстановку имени, если авторизован
                    if(isset($_SESSION['login'])) $new = M_Comments::comments_new($_GET['id'], $_SESSION['login'], $_POST['c_text']);
                    else $new = M_Comments::comments_new($_GET['id'], $_POST['name'], $_POST['c_text']);

                    if($new == true) header('location: index.php?c=articles&act=read&id=' . $_GET['id']);
                    else {

                        if(isset($_SESSION['login']))
                            $comment_form = $this->Template("view/comment_form.php", array('name' => $_SESSION['login'], 'c_text' => $_POST['c_text']));
                        else
                            $comment_form = $this->Template("view/comment_form.php", array('name' => $_POST['name'], 'c_text' => $_POST['c_text']));}
                } else {
                    if(isset($_SESSION['login'])) $comment_form = $this->Template("view/comment_form.php", array('name' => $_SESSION['login'], 'c_text' => ""));
                    else $comment_form = $this->Template("view/comment_form.php", array('name' => "", 'c_text' => ""));
                }

                //Формируем шаблон статьи
                $this->content = $this->Template("view/article.php", array('article' => $article, 'comments' => $comments_area, 'comment_form' => $comment_form));
            }
        }
    }

    //
    //Вывод списка статей в редакторе
    //
    public function action_editor(){
        $this->title .= "::Редактор";
        $mArticles = M_Articles::GetInstance();
        $articles = $mArticles->articles_all();
        $this->content = $this->Template("view/editor.php", array('articles' => $articles));
    }

    //
    //Редактирование статьи
    //
    public function action_edit(){
        $this->title .= '::Редактирование';

        //Проверка id и вывод данных статьи
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $mArticles = M_Articles::GetInstance();
            $article = $mArticles->articles_get($id);

            if(isset($article['id_article']))
                $this->content = $this->Template("view/edit.php",
                    array('title' => $article['title'], 'content' => $article['content']));
        }

        //Проверка полей и отправка данных формы	
        if($this->isPost())
        {
            $data = $mArticles->articles_edit($id, $_POST['title'], $_POST['content']);
            if ($data == true) {
                header('location: index.php?c=articles&act=read&id=' . $id);
                exit();
            }
            else {
                $error = "Не оставляйте поля пустыми!";
                $this->content = $this->Template("view/edit.php",
                    array('title' => $_POST['title'], 'content' => $_POST['content'], 'error' => $error));
            }
        }
    }

    //
    //Добавление новой статьи
    //
    public function action_add(){
        $this->title .= '::Добавление';
        $mArticles = M_Articles::GetInstance();

        if($this->isPost())
        {
            $data = $mArticles->articles_new($_POST['title'], $_POST['content']);
            if($data == true) {
                header('location: index.php');
                exit();
            } else {
                $this->content = $this->Template("view/new.php", array('title' => $_POST['title'], 'content' => $_POST['content']));
            }

        } else {
            $this->content = $this->Template("view/new.php", array('title' => "", 'content' => ""));
        }
    }

    //
    //Удаление статьи
    //
    public function action_delete(){
        $this->title .= '::Удаление';

        //Проверка id
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $mArticles = M_Articles::GetInstance();
            $article = $mArticles->articles_get($id);

            if(isset($article['id_article']))
                $this->content = $this->Template("view/delete.php", array('title' => $article['title']));
        }
        
        if(isset($_POST['delete'])) {
            $data = $mArticles->articles_delete($id);
            if($data == true) header('Location: index.php');
        }

        if(isset($_POST['cancel'])) header('Location: index.php?c=articles&act=read&id=' . $id);
    }

}

?>