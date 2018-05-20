<?php

/**
 * Description of controller_delete
 *
 * @author Vadym007
 */
class Controller_Delete extends Controller
{
    function __construct()
    {
	$this->model = new Model_Delete();
        $this->view = new View();
    }
    public function action_index()
    {
        if ($this->model->clear_table("goods")) {
            return $this->view->generate('delete_view.php', 'template_view.php');
        } else {
            return $this->view->generate('error_view.php', 'template_view.php');
        }  
    }
}
