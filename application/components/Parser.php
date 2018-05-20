<?php

/**
 * Description of Parser
 *
 * @author Vadym007
 */
require_once 'phpQuery/phpQuery/phpQuery.php';

class Parser 
{
    private $ch;
    
    public function __construct() 
    {
        $this->ch = curl_init();
    }
    
    public function set($name, $value)
    {
        curl_setopt($this->ch, $name, $value);
        return $this;
    }
    
    public function exec($url)
    {
        $this->set(CURLOPT_URL, $url);
        return curl_exec($this->ch);
    }
    
    public function __destruct() 
    {
       curl_close($this->ch) ;
    }
    




}
