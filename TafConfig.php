<?php

namespace Taf;

use PDO;

class TafConfig
{
    public static $db_instance = null;
    public static $mode_deploiement = false;
    public static $connected=null;
    public $tables = [];

    public $database_type="pgsql";//mysql
    public $host = "74.207.224.128";
    public $port = "5432";
    public $database_name = "etax";
    public $user = "etax";
    public $password = "~%tx12022#@";


    public function __construct()
    {
        // $this->allow_cors();
        $this->init_data();
    }
    public function init_data()
    {
        if ($this->tables == [] && $this->is_connected()) {
            switch ($this->database_type) {
                case 'pgsql':
                    $this->tables = $this->get_db()->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'")->fetchAll(PDO::FETCH_COLUMN);
                    break;
                case 'mysql':
                    $this->tables = $this->get_db()->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                    break;
                
                default:
                    // type de base de données inconnu
                    break;
            }            
        }
    }
    public function is_connected(){
        $this->get_db();
        return self::$connected;
    }
    public function get_db()
    {
        if (static::$db_instance == null) {
            try {
                static::$db_instance = new PDO("{$this->database_type}:host={$this->host};port={$this->port};dbname={$this->database_name};", $this->user, $this->password);
                //à commenter en mode production. Il permet de montrer les erreur explicitement
                static::$db_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connected=true;
            } catch (\Throwable $th) {
                // echo "false";
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
