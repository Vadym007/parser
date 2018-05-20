<?php

/**
 * Description of DB
 *
 * @author Vadym007
 */


class Database {
    private $connection;
    private static $instance; //The single instance

    public static function getInstance() 
    {
        if(!self::$instance) { // If no instance then make one
            self::$instance = new self();
        }
            return self::$instance;
    }

    private function __construct() 
    {
        $config = require_once 'application/configs/db_config.php';
        $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db_name'].';charset='.$config['charset'];
        try {
            $this->connection = new PDO($dsn, $config['user'], $config['password']);
        } catch (PdoException $e) {
            echo 'Error connection: '.$e->getMessage();
        } 
    }
    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
	// Get mysqli connection
    public function getConnection() 
    {
	return $this->connection;
    }
    
    public function clear_table($table)
    {
        $query = "DELETE FROM $table";
        $q = $this->connection->prepare($query);
        $q->execute();
        if($q->errorCode() != PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }
    }
    
    public function select($query) 
    {
        $q = $this->connection->prepare($query);
        $q->execute();
        
        if($q->errorCode() != PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }
        
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function insert($table, $object) {
        $columns = array();
        
        foreach($object as $key => $value) {
            $columns[] = $key;
            $masks[] = ":$key";
            
            if(is_null($value)) {
                $object[$key] = 'NULL';
            }
        }
        
        $columns_s = implode(',', $columns);
        $masks_s = implode(',', $masks);
        
        $query = "INSERT INTO $table ($columns_s) VALUES ($masks_s)";
        
        $q = $this->connection->prepare($query);
        $q->execute($object);
        
        if($q->errorCode() != PDO::ERR_NONE){
            $info = $q->errorInfo();
            die($info[2]);
        }
        
        return true;
    }
}

//    $db = Database::getInstance();
//    $pdo = $db->getConnection(); 
//    $sql_query = "SELECT foo FROM .....";
//    $result = $pdo->query($sql_query)