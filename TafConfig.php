<?php

namespace Taf;

use PDO;

class TafConfig
{
    public static $db_instance = null;
    public static $mode_deploiement = false;
    public static $connected=null;
    public $tables = [];

    public $host = "localhost";
    public $database_name = "new_e_tax";
    public $user = "root";
    public $password = "";


    public function __construct()
    {
        // $this->allow_cors();
        $this->init_data();
    }
    public function init_data()
    {
        if ($this->tables == [] && $this->is_connected()) {
            $this->tables = $this->get_db()->query("SHOW TABLES")->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    public function is_connected(){
        if(self::$connected==null){
            try {
                static::$db_instance = new PDO("mysql:host={$this->host};dbname={$this->database_name}", $this->user, $this->password);
                self::$connected= true;
            } catch (\Throwable $th) {
                self::$connected= false;
            }
        }
        return self::$connected;
    }
    public function get_db()
    {
        if (static::$db_instance == null) {
            try {
                static::$db_instance = new PDO("mysql:host={$this->host};dbname={$this->database_name}", $this->user, $this->password);
                //à commenter en mode production. Il permet de montrer les erreur explicitement
                static::$db_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connected=true;
            } catch (\Throwable $th) {
                echo "false";
                self::$connected=false;
            }

            // réglage du fuseau Horaire
            date_default_timezone_set("UTC");
        }
        return  static::$db_instance;
    }

    public function allow_cors()
    {
        return <<<HTML
            header("Access-Control-Allow-Origin: *");
            header('Access-Control-Allow-Credentials: true');
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type");
        HTML;
    }
}
