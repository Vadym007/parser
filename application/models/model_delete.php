<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_delete
 *
 * @author Vadym007
 */
class Model_Delete 
{
    public function clear_table($table)
    {
        $db = Database::getInstance();
        $res = $db->clear_table($table);
        return true;
    }
}
