<?php
namespace Taf;

class TableQuery
{
    public $table_name = null;
    public $description = [];
    public function __construct($table_name)
    {
        $this->table_name = $table_name;
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
        return "INSERT INTO {$this->table_name}(" . implode(",", $keys) . ") VALUES('" . implode("','", $values) . "')";
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
        if (empty($data_condition)) {
            return "";
        }
        $keyOperateurValue = array();
        foreach ($data_condition as $key => $value) {
            $keyOperateurValue[] = addslashes($key) . " " . $operation . " '" . addslashes($value) . "'";
        }
        return "where " . implode(" and ", $keyOperateurValue);
    }
    function dynamicInsert($assoc_array)
    {
      $keys = array();
      $values = array();
      foreach ($assoc_array as $key => $value) {
        $keys[] = $key;
        $values[] = addslashes($value);
      }
      return "INSERT INTO $this->table_name(" . implode(",", $keys) . ") VALUES('" . implode("','", $values) . "')";
    }
    
    function dynamicUpdate($assoc_array, $condition)
    {
      $keyEgalValue = array();
      foreach ($assoc_array as $key => $value) {
        $keyEgalValue[] = addslashes($key) . " = '" . addslashes($value) . "'";
      }
      return "update $this->table_name set " . implode(",", $keyEgalValue) . " " . $condition;
    }

}
