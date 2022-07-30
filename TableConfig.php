<?php

class TableConfig
{
    public $table_name = null;
    public $description = [];
    public static $mode_deploiement = false;

    public function __construct($table_name)
    {
        $this->table_name = $table_name;
        $this->init_table_data();
    }
    private function init_table_data()
    {
        if ($this->description == null) {
            // $this->description = $connexion->query("desc $this->table_name")->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    /**
     * generate the insert query to tablename with some data
     *
     * @param [type] $data_to_insert
     * @return string la requete entière
     */
    public function insert_query($data_to_insert)
    {
        $keys = array();
        $values = array();
        foreach ($data_to_insert as $key => $value) {
            $keys[] = $key;
            $values[] = addslashes($value);
        }
        return "INSERT INTO {$this->table_name}(`" . implode("`,`", $keys) . "`) VALUES('" . implode("','", $values) . "')";
    }
    function update_query($data_to_update, $condition)
    {
        $keyEgalValue = array();
        foreach ($data_to_update as $key => $value) {
            $keyEgalValue[] = addslashes($key) . " = '" . addslashes($value) . "'";
        }
        return "update  {$this->table_name} set " . implode(",", $keyEgalValue) . " " . $condition;
    }
    function dynamicCondition($data_condition, $operation)
    {
        if (count($data_condition) == 0) {
            return "";
        }
        $keyOperateurValue = array();
        foreach ($data_condition as $key => $value) {
            $keyOperateurValue[] = addslashes($key) . " " . $operation . " '" . addslashes($value) . "'";
        }
        return "where " . implode(" and ", $keyOperateurValue);
    }

    function get_url()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = "https://";
        } else {
            $url = "http://";
        }
        // Append the host(domain name, ip) to the URL.   
        $url .= $_SERVER['HTTP_HOST'];

        // Append the requested resource location to the URL   
        $url .= $_SERVER['REQUEST_URI'];

        return $url;
    }
}
