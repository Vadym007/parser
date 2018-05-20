<?php

class Сontroller_P extends Controller
{
    public function __construct() {
        $this->view = new View();
    }

    public function action_index() 
    {
        echo 'parser'; die;
//        if(Parser::getData("https://glem.com.ua/zhenskie-platya-optom/")){
//            $this->view->generate('parse_view.php', 'template_view.php');
//        } else {
//            $this->view->generate('error_view.php', 'template_view.php');
//        }
    }
}
