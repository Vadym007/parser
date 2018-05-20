<?php

/**
 * Description of model_data
 *
 * @author Vadym007
 */
class Model_Data {
    public function get_data() 
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM goods";
        $res = $db->select($query);
        return $res;
    }
}
