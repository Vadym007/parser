<?php

class Controller_Data extends Controller
{
        function __construct()
	{
		$this->model = new Model_Data();
		$this->view = new View();
	}
	
	function action_index()
	{
            $data = $this->model->get_data();
            $this->view->generate('data_view.php', 'template_view.php', $data);
	}
}
