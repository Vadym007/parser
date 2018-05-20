<?php

class Controller_Put extends Controller
{
    public function __construct() {
        $this->view = new View();
    }

    public function action_index() 
    {
        if(Parser::getData("https://glem.com.ua/zhenskie-platya-optom/")){
            $this->view->generate('parse_view.php', 'template_view.php');
        } else {
            $this->view->generate('error_view.php', 'template_view.php');
        }
    }
}